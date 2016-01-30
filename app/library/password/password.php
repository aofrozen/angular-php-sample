<?php

namespace app\library\password;

use \Phalcon\Di\Injectable;

class password extends Injectable
    {
    /**
     * Generate a PBKDF2 key derivation of a supplied password
     *
     * This is a hash_pbkdf2() implementation for PHP versions 5.3 and 5.4.
     * @link http://www.php.net/manual/en/function.hash-pbkdf2.php
     *
     * @param string $algo
     * @param string $password
     * @param string $salt
     * @param int $iterations
     * @param int $length
     * @param bool $rawOutput
     *
     * @return string
     */
        public function compat_pbkdf2($algo, $password, $salt, $iterations, $length = 0, $rawOutput = false)
            {
                // check for hashing algorithm
                if (!in_array(strtolower($algo), hash_algos()))
                    {
                        trigger_error(sprintf(
                            '%s(): Unknown hashing algorithm: %s',
                            __FUNCTION__, $algo
                        ), E_USER_WARNING);
                        return false;
                    }

                // check for type of iterations and length
                foreach (array(4 => $iterations, 5 => $length) as $index => $value)
                    {
                        if (!is_numeric($value))
                            {
                                trigger_error(sprintf(
                                    '%s() expects parameter %d to be long, %s given',
                                    __FUNCTION__, $index, gettype($value)
                                ), E_USER_WARNING);
                                return null;
                            }
                    }

                // check iterations
                $iterations = (int)$iterations;
                if ($iterations <= 0)
                    {
                        trigger_error(sprintf(
                            '%s(): Iterations must be a positive integer: %d',
                            __FUNCTION__, $iterations
                        ), E_USER_WARNING);
                        return false;
                    }

                // check length
                $length = (int)$length;
                if ($length < 0)
                    {
                        trigger_error(sprintf(
                            '%s(): Iterations must be greater than or equal to 0: %d',
                            __FUNCTION__, $length
                        ), E_USER_WARNING);
                        return false;
                    }

                // check salt
                if (strlen($salt) > PHP_INT_MAX - 4)
                    {
                        trigger_error(sprintf(
                            '%s(): Supplied salt is too long, max of INT_MAX - 4 bytes: %d supplied',
                            __FUNCTION__, strlen($salt)
                        ), E_USER_WARNING);
                        return false;
                    }

                // initialize
                $derivedKey = '';
                $loops = 1;
                if ($length > 0)
                    {
                        $loops = (int)ceil($length / strlen(hash($algo, '', $rawOutput)));
                    }

                // hash for each blocks
                for ($i = 1; $i <= $loops; $i++)
                    {
                        $digest = hash_hmac($algo, $salt . pack('N', $i), $password, true);
                        $block = $digest;
                        for ($j = 1; $j < $iterations; $j++)
                            {
                                $digest = hash_hmac($algo, $digest, $password, true);
                                $block ^= $digest;
                            }
                        $derivedKey .= $block;
                    }

                if (!$rawOutput)
                    {
                        $derivedKey = bin2hex($derivedKey);
                    }

                if ($length > 0)
                    {
                        return substr($derivedKey, 0, $length);
                    }

                return $derivedKey;
            }

        public function generateSalt($length)
            {
                return base64_encode(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
            }

        public function securePass($password, $alg, $length, $iterations)
            {
                $salt = $this->generateSalt($length);
                $hash = $this->compat_pbkdf2($alg, $password, $salt, $iterations, $length);
                $data['alg'] = $alg;
                $data['iterations'] = $iterations;
                $data['salt'] = $salt;
                $data['hash'] = $hash;
                return $data;
            }

        public function testPass($password, $salt, $length, $iterations, $alg)
            {
                $hash_test = $this->compat_pbkdf2($alg, $password, $salt, $iterations, $length);
                return $hash_test;
            }
    }