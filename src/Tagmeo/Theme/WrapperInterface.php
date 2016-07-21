<?php

namespace Tagmeo\Theme;

interface WrapperInterface
{
    /**
     * @return string Wrapper template
     */
    public function wrap();

    /**
     * @return string Wrapped template
     */
    public function unwrap();

    /**
     * @return string Slug of the WrapperInterface
     */
    public function slug();
}
