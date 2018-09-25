External http transport that integrates nicely with Symfony.

Transport is defined to return a HttpFoundation\Request instance in all cases, which can be directly returned from a Controller action etc.

To check for errors, call [isOk()](https://github.com/symfony/symfony/blob/2.4/src/Symfony/Component/HttpFoundation/Response.php#L1189) on the Response.
