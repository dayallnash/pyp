<?php

namespace App\Exceptions;

use App\Message\PostToDistribute;
use App\MessageHandler\PostToDistributeHandler;
use Exception;

/**
 * Class PostDistributionException.
 *
 * Throw this exception when a post fails to distribute.
 *
 * @see PostToDistribute
 * @see PostToDistributeHandler
 */
class PostDistributionException extends Exception
{
}
