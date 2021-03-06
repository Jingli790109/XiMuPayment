<?php

include_once( __DIR__ . '/vendor/autoload.php');

use XiMu\Alipay\Alipay;
use XiMu\Alipay\Config\Config;

$publickKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2nhvM2t1jAlvfs/8nE2BGqZjfBlKuJyzDVe34+aELJrsmEWns1zT+lFjmGvp5QPKPc+GpOXIcLY6YbA2ZXGs8k8pPeYpvgmEOYREroPnW0gV+gtL2a9+7ijpKCeH0SfCdaDWS8heyilHSJcm+WL+6+wxVaVXCM98vs/FvL2II52TNVMPCU4l1GeVJTFURnVcwMbXEv8tm0/H8lBm4DUYhbsw3EuS+/JqxoTIkamJT+4fGKjgzP5sY9CVo4FNbNwhy2qtVNA+1AIsQzZEIiACrn4rJ6fFAduJYJgjAVDWfKz/i4neLfqKyp1R+HeaE/IX0XBlDq3xab0vAppY4lPehQIDAQAB';

$privateKey = 'MIIEowIBAAKCAQEA1tnE7RikIGM0af0d2Z9eCyfhFjJLhHrNpAQVThfaITSfimEwbMpVEo/ovI9aSES4xvxgxx9nJjR03VDlB+eUX4pOE45wNgwq8tt07gtz2pqoz1X6S0FZwE9Sypd2N7o1rkmdIBd0Hifgl3UyCaMVvpOhH+Lz7hEjseNvFq2bD8ZKGZz22yipxBj5Q0Vi+UTf6o7ObEMrxAMFQovi7y23iOMJ9SXA0kro6e0AFaH5VrQJxscluhaD90aBWFAvhyY69X4TFD2o9zb1px+rsmGASVM5+1cvg88kn6jXzGa/+R2O/EKHEVz2OcfXVNJfNQ7cixPJ2XHMZn5Ll2PNtNdpCwIDAQABAoIBAFZ0oy3VcJYn1XuhA1YDL1DF52mLK6BP2lhZ3EjkiD48csa4fUgJ01n41uqptObH4KMSA8+c72EMUsqMNt2LbsVTXj+4XaciFlZLwS3ZJfGdsrhcHLATyA3+3Y3wjP23zMopjTTQu0U3PsUzv8LpAWMC/R9bsFToSLjYkGdLzM33fC4IWgwFZYUJ4bDoNj9inIJqYDcI69whaouoP1pDQEqwBLJXOmS66b6oa1EZEAPs0CfeigdleJ4dsn2dvZMFE3s9ahj6u+FfAumPw9wobXFszwk36R5ul2X15/Ke0aoQeYaCdaqMTb8UsIZ8nnLENkKTwhoF52LTE+T2HY3s5eECgYEA7cSKzAqdiohiU3E4Vxo1/XnmOs9hn4UvTaBvb+au3Yailmkt5O/inZORSnx6/QyqDXSKqf6HENAZBoR6rnGM4bQFdWq1zrA+bDAOAPQ+V5E6iMprA2Uj615XIwypSY+SIZrElalOJeSdbqDpuTqrFIL5m2rR7noX+BMu3LUb8iUCgYEA51NbecoQfCCnyd2agkPZnLFvqp/wAMLfTR1dxPoafhTi/uuQ6nwKk2OKA2WfWEQDMJQaX3wyOJpSel5B4k4Rz5qJTKQ96eygWnn0iAfBI5NhkeANRVYAyP629CbX3RFX09JeQbvo1gvpEEIW9lw2kwqvKXHY5KJASUPi6PYAT28CgYEAqChOuYvwa5+VWspCPGgPMxvZVlKBCp7ZG5+R8KRHm+iyaIouqTF3JlbNdM6g9QLV6fo4b43R3HQwnslnMqSgLKhzC93Sg0FmhIFgBhC3XpsZuNDf6mDHjJkGK4Wy3JGrmhSpX+eDm40aQrmPUy9I+5K+Ecr0eiLjfNGkXPfBsfkCgYAOAJa17YxH5zYg2wAiSHcgrADlZB8D/MfFhDSL2tPFs//1jE7OUsnVGB5fjEQz9JH4284o33yuvnClpZT5XN5pIaKT+BEjWsZuE5nAri97ts0eJmDHRKhxgjGS39MLN6SIVuCLvBMg+cGW0VlQek53YhsuOAz38fZQOQLANTvZkwKBgDacNIyyZTRww+fdUWxTqFJcYpsn3IkdZ0KTffRY/vLP3+6jOBNnifLNUUtUY7ZW7cUY8jUuU/rrzGYC6Ofgt4F7V+IvsAX8mpwULwTX399iyHkWVG0foPopc/KW84O0Sw37AkXMlzO75tN94Y88+UE4JEu4lgIdGlpK3GRwPGtK';

$config = new Config();
$config->setAppId('2016073100133264')
    ->setPId('2088102169299070')
    ->setMerchantPrivateKey($privateKey)
    ->setAlipayPublicKey($publickKey)
    ->setPreCreateNotifyUrl('http://xypay.andmall.com/payment/alipay/notify')
;
$alipay = new Alipay($config);
$result = $alipay->alipayTradePreCreate('XIMU123456789', '1', '测试支付');
print_r($result);
