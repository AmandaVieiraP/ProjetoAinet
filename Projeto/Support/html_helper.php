<?php
function is_selected($current, $expected, $output = 'selected')
{
    if ($current === $expected) {
        return $output;
    }
}