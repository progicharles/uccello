<?php $value = $field->uitype->getFormattedValueToDisplay($field, $record); ?>
@if ($value)
<a href="tel:{{ $value }}" class="primary-text" style="white-space: nowrap">{{ $value }}</a>
@endif