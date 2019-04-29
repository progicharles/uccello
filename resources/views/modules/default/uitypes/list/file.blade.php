<?php
$value = uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record);
$valueParts = explode(';', $value);
$fileName = $valueParts[0];
?>
@if (count($valueParts) === 2)
<a href="{{ ucroute('uccello.download', $domain, $module, [ 'id' => $record->getKey(), 'field' => $field->column ]) }}"
    title="{{ uctrans('button.download_file', $module) }}"
    class="primary-text">
    {{ $fileName }}
</a>
@endif