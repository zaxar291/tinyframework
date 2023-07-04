<?php

namespace bin\Entities;

class RouteSegment
{
    public string $segmentTemplate;
    public string $isSegmentRequired;
    public bool $isPatternSegment;
    public string $segmentDefaultValue;

    public function __construct(
        string $segmentTemplate,
        string $isSegmentRequired,
        bool $isPatternSegment,
        string $segmentDefaultValue
    ) {
        $this->segmentTemplate = $segmentTemplate;
        $this->isSegmentRequired = $isSegmentRequired;
        $this->isPatternSegment = $isPatternSegment;
        $this->segmentDefaultValue = $segmentDefaultValue;
    }
}