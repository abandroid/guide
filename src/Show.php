<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide;

class Show
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $results;

    /**
     * @param string $type
     * @param string $label
     * @param array $options
     */
    public function __construct($type, $label, $options = [])
    {
        $this->type = $type;
        $this->label = $label;
        $this->options = $options;

        $this->results = [];
    }

    /**
     * @param array $show
     * @return array|static
     */
    public static function fromArray(array $show)
    {
        $type = $show['type'];
        $label = $show['label'];

        unset($show['type']);
        unset($show['label']);

        $show = new static($type, $label, $show);

        return $show;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $results
     * @return $this
     */
    public function setResults(array $results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }
}