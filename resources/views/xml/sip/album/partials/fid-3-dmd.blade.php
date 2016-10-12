<mets:dmdSec ID="fid1-3-dmd">
  <mets:mdWrap MDTYPE="DC">
    <mets:xmlData>
      <dc:record xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <dc:title>{{ $fid1_3_dmd_title }}</dc:title>
        <dc:source>{{ $fid1_3_dmd_source }}</dc:source>
        <dc:description>{{ $fid1_3_dmd_description }}</dc:description>
        <dc:identifier>{{ $fid1_3_dmd_identifier }}</dc:identifier>
        @if (!empty($fid1_3_dmd_date)) <dc:date>{{ $fid1_3_dmd_date }}</dc:date> @endif
        <dcterms:tableOfContents>1</dcterms:tableOfContents>
        @if (!empty($fid1_3_dmd_isFormatOf)) <dcterms:isFormatOf>{{ $fid1_3_dmd_isFormatOf }}</dcterms:isFormatOf> @endif
      </dc:record>
    </mets:xmlData>
  </mets:mdWrap>
</mets:dmdSec>
