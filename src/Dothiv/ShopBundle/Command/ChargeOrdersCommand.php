<?php

namespace Dothiv\ShopBundle\Command;

use Dothiv\BusinessBundle\BusinessEvents;
use Dothiv\BusinessBundle\Entity\Banner;
use Dothiv\BusinessBundle\Entity\Domain;
use Dothiv\BusinessBundle\Entity\Invoice;
use Dothiv\BusinessBundle\Event\DomainEvent;
use Dothiv\BusinessBundle\Repository\BannerRepositoryInterface;
use Dothiv\BusinessBundle\Repository\DomainRepositoryInterface;
use Dothiv\BusinessBundle\Repository\RegistrarRepositoryInterface;
use Dothiv\BusinessBundle\Repository\UserRepositoryInterface;
use Dothiv\BusinessBundle\Service\UserServiceInterface;
use Dothiv\LandingpageBundle\Service\LandingpageServiceInterface;
use Dothiv\ShopBundle\Event\OrderEvent;
use Dothiv\ShopBundle\ShopEvents;
use Dothiv\UserReminderBundle\Entity\UserReminder;
use Dothiv\UserReminderBundle\Repository\UserReminderRepositoryInterface;
use Dothiv\ShopBundle\Entity\Order;
use Dothiv\ShopBundle\Repository\OrderRepositoryInterface;
use Dothiv\ShopBundle\Service\InvoiceServiceInterface;
use Dothiv\ShopBundle\Service\OrderMailerInterface;
use Dothiv\ValueObject\EmailValue;
use Dothiv\ValueObject\IdentValue;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ChargeOrdersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('shop:orders:charge')
            ->setDescription('Charge new orders');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var OrderRepositoryInterface $orderRepo */
        /** @var RegistrarRepositoryInterface $registrarRepo */
        /** @var BannerRepositoryInterface $bannerRepo */
        /** @var DomainRepositoryInterface $domainRepo */
        /** @var UserRepositoryInterface $userRepo */
        /** @var InvoiceServiceInterface $invoiceService */
        /** @var UserReminderRepositoryInterface $domainConfigNotificationRepo */
        /** @var OrderMailerInterface $mailer */
        /** @var UserServiceInterface $userService */
        /** @var EventDispatcherInterface $eventDispatcher */
        $orderRepo                    = $this->getContainer()->get('dothiv.repository.shop_order');
        $registrarRepo                = $this->getContainer()->get('dothiv.repository.registrar');
        $bannerRepo                   = $this->getContainer()->get('dothiv.repository.banner');
        $domainRepo                   = $this->getContainer()->get('dothiv.repository.domain');
        $userRepo                     = $this->getContainer()->get('dothiv.repository.user');
        $invoiceService               = $this->getContainer()->get('dothiv.shop.invoice');
        $domainConfigNotificationRepo = $this->getContainer()->get('dothiv.repository.userreminder');
        $mailer                       = $this->getContainer()->get('dothiv.shop.mailer.order');
        $userService                  = $this->getContainer()->get('dothiv.businessbundle.service.user');
        $eventDispatcher              = $this->getContainer()->get('dothiv.business.event_dispatcher');

        \Stripe::setApiKey($this->getContainer()->getParameter('stripe_secret_key'));

        foreach ($orderRepo->findNew() as $order) {
            $invoice = $invoiceService->createInvoice($order);
            /** @var Order $order */
            $charge = \Stripe_Charge::create(array(
                'amount'      => $invoice->getTotalPrice(),
                'currency'    => strtolower($order->getCurrency()->toScalar()),
                'card'        => $order->getStripeToken()->toScalar(),
                'description' => $invoice->getItemDescription()
            ));
            $order->setStripeCharge(new IdentValue($charge->id));
            $order->setInvoice($invoice);
            $orderRepo->persist($order)->flush();
            $mailer->send($order, $invoice);
            foreach ($this->getContainer()->getParameter('dothiv_business.invoice_copy') as $extraRecipient) {
                $mailer->send($order, $invoice, new EmailValue($extraRecipient['email']), $extraRecipient['name']);
            }
            // Create domain
            $user = $userService->getOrCreateUser($order->getEmail()->toScalar(), $order->getFirstname(), $order->getLastname());

            $domain = new Domain();
            $domain->setName($order->getDomain()->toScalar());
            $domain->setOwner($user);
            $domain->setRegistrar($registrarRepo->getByExtId('1508-KS'));
            $userRepo->persist($user);
            $domainRepo->persist($domain)->flush();
            $userRepo->flush();
            $domainRepo->flush();

            // Create clickcounter-counter config
            $banner = new Banner();
            if ($order->getRedirect()->isDefined()) {
                $banner->setRedirectUrl($order->getRedirect()->get());
            }
            $domain->setActiveBanner($banner);
            $banner->setLanguage($order->getLanguage());
            $banner->setPosition('top');
            $banner->setPositionAlternative('top');
            $bannerRepo->persist($banner)->flush();
            $domainRepo->persist($domain)->flush();

            // Do not notify about configuration
            $domainConfigReminder = new UserReminder();
            $domainConfigReminder->setIdent($domain);
            $domainConfigReminder->setType(new IdentValue('configuration'));
            $domainConfigNotificationRepo->persist($domainConfigReminder)->flush();

            // Notify listeners
            $eventDispatcher->dispatch(BusinessEvents::DOMAIN_REGISTERED, new DomainEvent($domain));
            $eventDispatcher->dispatch(ShopEvents::ORDER_CHARGED, new OrderEvent($order, $domain));

            $output->writeln(
                sprintf('Processed order for %s by %s.', $order->getDomain()->toUTF8(), $order->getEmail())
            );
            $this->showOrder($output, $order, $invoice);
        }
    }

    /**
     * @param OutputInterface $output
     * @param Order           $order
     */
    protected function showOrder(OutputInterface $output, Order $order, Invoice $invoice)
    {
        $table = new TableHelper();
        $table->setHeaders(array('Name', 'Value'));
        $table->addRow(array('Domain', $order->getDomain()->toUTF8()));
        $table->addRow(array('Duration', $order->getDuration()));
        $table->addRow(array('Price', ($invoice->getTotalPrice() / 100) . ' ' . ($order->getCurrency() == Order::CURRENCY_EUR ? '€' : '$')));
        $table->addRow(array('Name', $order->getFirstname() . ' ' . $order->getLastname()));
        $table->addRow(array('Email', $order->getEmail()));
        $table->addRow(array('Locality', $order->getLocality()));
        if ($order->getLocality2()->isDefined()) {
            $table->addRow(array('Locality (ctd.)', $order->getLocality2()->get()));
        }
        $table->addRow(array('City', $order->getCity()));
        $table->addRow(array('Country', $order->getCountry()->toScalar()));
        if ($order->getOrganization()->isDefined()) {
            $table->addRow(array('Organization', $order->getOrganization()->get()));
        }
        $table->addRow(array('Phone', $order->getPhone()));
        if ($order->getFax()->isDefined()) {
            $table->addRow(array('Fax', $order->getFax()->get()));
        }
        $table->render($output);
    }
}
