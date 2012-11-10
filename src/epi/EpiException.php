<?php namespace Epi;

/**
 * @author Jaisen Mathai <jaisen@jmathai.com>
 * @extends \Exception
 */
class EpiException extends \Exception
{
	/**
	 * @param unknown $exception
	 */
	public static function raise($exception)
  {
    $useExceptions = Epi::getSetting('exceptions');
    if($useExceptions)
    {
      throw new $exception($exception->getMessage(), $exception->getCode());
    }
    else
    {
      echo sprintf("An error occurred and you have <strong>exceptions</strong> disabled so we're displaying the information.
                    To turn exceptions on you should call: <em>Epi::setSetting('exceptions', true);</em>.
                    <ul><li>File: %s</li><li>Line: %s</li><li>Message: %s</li><li>Stack trace: %s</li></ul>",
                    $exception->getFile(), $exception->getLine(), $exception->getMessage(), nl2br($exception->getTraceAsString()));
    }
  }
}

/**
 * 
 * @author Jaisen Mathai <jaisen@jmathai.com>
 *
 */
class EpiCacheException extends EpiException{}

/**
 * 
 * @author Jaisen Mathai <jaisen@jmathai.com>
 *
 */
class EpiCacheTypeDoesNotExistException extends EpiCacheException{}

/**
 * 
 * @author Jaisen Mathai <jaisen@jmathai.com>
 *
 */
class EpiCacheMemcacheClientDneException extends EpiCacheException{}

/**
 * 
 * @author Jaisen Mathai <jaisen@jmathai.com>
 *
 */
class EpiCacheMemcacheConnectException extends EpiCacheException{}

/**
 * 
 * @author Jaisen Mathai <jaisen@jmathai.com>
 *
 */
class EpiDatabaseException extends EpiException{}

/**
 * 
 * @author Jaisen Mathai <jaisen@jmathai.com>
 *
 */
class EpiDatabaseConnectionException extends EpiDatabaseException{}

/**
 * 
 * @author Jaisen Mathai <jaisen@jmathai.com>
 *
 */
class EpiDatabaseQueryException extends EpiDatabaseException{}

/**
 * 
 * @author Jaisen Mathai <jaisen@jmathai.com>
 *
 */
class EpiSessionException extends EpiException{}
