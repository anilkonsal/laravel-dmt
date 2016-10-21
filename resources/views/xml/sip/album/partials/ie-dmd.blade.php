<mets:dmdSec ID="ie-dmd">
  <mets:mdWrap MDTYPE="DC">
    <mets:xmlData>
      <dc:record xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <dc:identifier>{{ $ie_dmd_identifier }}</dc:identifier>
        <dc:title>{{ $ie_dmd_title }}</dc:title>
        @if (!empty($ie_dmd_creator)) <dc:creator>{{ $ie_dmd_creator }}</dc:creator> @endif
        @if (!empty($ie_dmd_source)) <dc:source>{{ $ie_dmd_source }}</dc:source> @endif
        @if (!empty($ie_dmd_type))
            @foreach ($ie_dmd_type as $type)
                <dc:type>{{ $type }}</dc:type>
            @endforeach
        @endif
        <dcterms:accessRights>{{ $ie_dmd_accessRights }}</dcterms:accessRights>
        @if (!empty($ie_dmd_date)) <dc:date>{{ $ie_dmd_date }}</dc:date> @endif
        @if (!empty($ie_dmd_isFormatOf)) <dcterms:isFormatOf>{{ $ie_dmd_isFormatOf }}</dcterms:isFormatOf> @endif
        <dcterms:isReferencedBy>ACMS</dcterms:isReferencedBy>
      </dc:record>
    </mets:xmlData>
  </mets:mdWrap>
</mets:dmdSec>
