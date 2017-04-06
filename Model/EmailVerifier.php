<?php

namespace LinusShops\Kickbox\Model;

use LinusShops\Kickbox\Api\EmailVerifierInterface;

/**
 * Verifies emails against kickbox.io.
 *
 * @author Sam Schmidt <samuel@dersam.net>
 */
class EmailVerifier implements EmailVerifierInterface
{
    /** @var \LinusShops\Kickbox\Model\EmailFactory  */
    private $emailFactory;

    public function __construct(
        EmailFactory $emailFactory
    ){
        $this->emailFactory = $emailFactory;
    }

    /**
     * Verify an email against Kickbox.io. Returns the response model. Use this
     * if you require access to the fields returned by Kickbox.
     *
     * @param string $email
     * @param array $options
     *
     * @return \LinusShops\Kickbox\Model\Email
     */
    public function verify($email, $options = ['timeout' => 6000])
    {
        return $this->emailFactory
            ->create($options)
            ->load($email)
            ->verify($options);
    }

    /**
     * Verify an email against kickbox.io. Returns a boolean, indicating if
     * the email is valid. Use this if you just want to know if the email
     * can be delivered. 'deliverable' and 'risky' will both be considered
     * valid and return true.
     *
     * If there is an undefined response, or the request failed, `null` will
     * be returned, indicating that the check failed to complete and should
     * be retried. This is to avoid flagging emails as invalid when the response
     * was not conclusive.
     *
     * @param string $email
     * @param array $options
     *
     * @return bool|null
     */
    public function verifyIsDeliverable($email, $options = ['timeout' => 6000])
    {
        return $this->verify($email, $options)->isDeliverable();
    }
}
