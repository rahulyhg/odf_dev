<?php
/**
 *
 * This file is part of phpFastCache.
 *
 * @license MIT License (MIT)
 *
 * For full copyright and license information, please see the docs/CREDITS.txt file.
 *
 * @author Lucas Brucksch <support@hammermaps.de>
 *
 */

namespace phpFastCache\Drivers\Zendshm;

use phpFastCache\Core\Item\ExtendedCacheItemInterface;
use phpFastCache\Core\Item\ItemBaseTrait;
use phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface;
use phpFastCache\Drivers\Zendshm\Driver as ZendSHMDriver;
use phpFastCache\Exceptions\phpFastCacheInvalidArgumentException;

/**
 * Class Item
 * @package phpFastCache\Drivers\Zendshm
 */
class Item implements ExtendedCacheItemInterface
{
    use ItemBaseTrait;

    /**
     * Item constructor.
     * @param \phpFastCache\Drivers\Zendshm\Driver $driver
     * @param $key
     * @throws phpFastCacheInvalidArgumentException
     */
    public function __construct(ZendSHMDriver $driver, $key)
    {
        if (is_string($key)) {
            $this->key = $key;
            $this->driver = $driver;
            $this->driver->setItem($this);
            $this->expirationDate = new \DateTime();
        } else {
            throw new phpFastCacheInvalidArgumentException(sprintf('$key must be a string, got type "%s" instead.', gettype($key)));
        }
    }

    /**
     * @param ExtendedCacheItemPoolInterface $driver
     * @throws phpFastCacheInvalidArgumentException
     * @return static
     */
    public function setDriver(ExtendedCacheItemPoolInterface $driver)
    {
        if ($driver instanceof ZendSHMDriver) {
            $this->driver = $driver;

            return $this;
        } else {
            throw new phpFastCacheInvalidArgumentException('Invalid driver instance');
        }
    }
}