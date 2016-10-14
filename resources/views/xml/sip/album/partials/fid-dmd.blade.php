<mets:dmdSec ID="{{ $fidx-y-dmd }}">
  <mets:mdWrap MDTYPE="DC">
    <mets:xmlData>
      <dc:record xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <dc:title>{{ $fidx_y_dmd_title }}</dc:title>
        <dc:source>{{ $fidx_y_dmd_source }}</dc:source>
        <dc:description>{{ $fidx_y_dmd_description }}</dc:description>
        <dc:identifier>{{ $fidx_y_dmd_identifier }}</dc:identifier>
        @if (!empty($fidx_y_dmd_date)) <dc:date>{{ $fidx_y_dmd_date }}</dc:date> @endif
        <dcterms:tableOfContents>{{ $fidx_y_dmd_tableOfContents }}</dcterms:tableOfContents>
        @if (!empty($fidx_y_dmd_isFormatOf)) <dcterms:isFormatOf>{{ $fidx_y_dmd_isFormatOf }}</dcterms:isFormatOf> @endif
      </dc:record>
    </mets:xmlData>
  </mets:mdWrap>
</mets:dmdSec>
