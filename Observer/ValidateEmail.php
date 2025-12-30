<?php

namespace LinusShops\Kickbox\Observer;

use LinusShops\Kickbox\Model\EmailVerifier;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Observer to validate email addresses using Kickbox.
 *
 * The observer allows other modules to hook kickbox into email validation without
 * depending directly on the Kickbox module.
 *
 * Note that the module already provides an email validation endpoint, however, for
 * extra uses this is required. For instance, validating emails during customer registration
 * or checkout. When we want to know if the email is valid but also if a customer account
 * already exists with that email.
 *
 * This used to be a hard dependency in the checkout module. Now, the endpoint is moved in Common
 * and can be used with or without Kickbox.
 *
 * @author Alex Ghiban <alex@ghiban.com>
 */
class ValidateEmail implements ObserverInterface
{

    /**
     * @var \LinusShops\Kickbox\Model\EmailVerifier
     */
    private EmailVerifier $emailVerifier;

    public function __construct(
        EmailVerifier $emailVerifier
    ) {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $email = $observer->getData('email');
        $response = $observer->getData('response'); // send results back in objectFactory response.
        if (empty($email)) {
            return;
        }
        $kickboxResult = $this->emailVerifier->verify($email);

        $response->setData('kickbox_result', $kickboxResult); // for further use if needed
        $response->setData('is_kickbox_deliverable', $kickboxResult->isDeliverable()); //what should be checked.
    }
}