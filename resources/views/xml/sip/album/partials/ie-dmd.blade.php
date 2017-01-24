<mets:dmdSec ID="ie-dmd">
  <mets:mdWrap MDTYPE="DC">
    <mets:xmlData>
      <dc:record xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <dc:identifier>{{ $ie_dmd_identifier }}</dc:identifier>
        <dc:title>{{ $ie_dmd_title }}</dc:title>
        @if (!empty($ie_dmd_relation)) <dc:relation>{{ $ie_dmd_relation }}</dc:relation> @endif
        @if (!empty($ie_dmd_creator)) <dc:creator>{{ $ie_dmd_creator }}</dc:creator> @endif
        @if (is_array($ie_dmd_source))
          @if (!empty($ie_dmd_source))
              @foreach ($ie_dmd_source as $source)
                  <dc:source>{{ $source }}</dc:source>
              @endforeach
          @endif
        @else
          @if (!empty($ie_dmd_source)) <dc:source>{{ $ie_dmd_source }}</dc:source> @endif
        @endif
        @if (is_array($ie_dmd_type))
          @if (!empty($ie_dmd_type))
              @foreach ($ie_dmd_type as $type)
                  <dc:type>{{ $type }}</dc:type>
              @endforeach
          @endif
        @else
          @if (!empty($ie_dmd_type)) <dc:type>{{ $ie_dmd_type }}</dc:type> @endif
        @endif
        <dcterms:accessRights>{{ $ie_dmd_accessRights }}</dcterms:accessRights>
        @if (!empty($ie_dmd_date)) <dc:date>{{ $ie_dmd_date }}</dc:date> @endif
        @if (!empty($ie_dmd_isFormatOf)) <dcterms:isFormatOf>{{ $ie_dmd_isFormatOf }}</dcterms:isFormatOf> @endif
        <dcterms:isReferencedBy>{{ $ie_dmd_isReferencedBy }}</dcterms:isReferencedBy>
      </dc:record>
    </mets:xmlData>
  </mets:mdWrap>
</mets:dmdSec>
