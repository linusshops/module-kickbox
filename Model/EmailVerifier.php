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
        // TODO: Implement verify() method.
    }

    /**
     * Verify an email against kickbox.io. Returns a boolean, indicating if
     * the email is valid. Use this if you just want to know if the email
     * can be delivered. 'deliverable' and 'risky' will both be considered
     * valid and return true.
     *
     * @param string $email
     * @param array $options
     *
     * @return bool
     */
    public function verifyIsDeliverable($email, $options = ['timeout' => 6000])
    {
        // TODO: Implement verifyIsDeliverable() method.
}}
