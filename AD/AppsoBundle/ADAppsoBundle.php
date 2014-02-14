<?php

namespace AD\AppsoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ADAppsoBundle extends Bundle
{
    public function getParent()
    {
        return "eZDemoBundle";
    }
}
