<?php $x=1; ?>
<mets:mets xmlns:mets="http://www.loc.gov/METS/">

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
  @if(!empty($fid1_1_amd_fileOriginalName))
  <mets:dmdSec ID="fid1-{{ $x }}-dmd">
    <mets:mdWrap MDTYPE="DC">
      <mets:xmlData>
        <dc:record xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:mods="http://www.loc.gov/mods/v3">
          <dc:title>{{ $fid1_1_dmd_title }}</dc:title>
          <dc:source>{{ $fid1_1_dmd_source }}</dc:source>
          <dc:description>{{ $fid1_1_dmd_description }}</dc:description>
          <dc:identifier>{{ $fid1_1_dmd_identifier }}</dc:identifier>
          @if (!empty($fid1_1_dmd_date)) <dc:date>{{ $fid1_1_dmd_date }}</dc:date> @endif
          <dcterms:tableOfContents>1</dcterms:tableOfContents>
		  @if (!empty($fid1_1_dmd_isFormatOf)) <dcterms:isFormatOf>{{ $fid1_1_dmd_isFormatOf }}</dcterms:isFormatOf> @endif
        </dc:record>
      </mets:xmlData>
    </mets:mdWrap>
  </mets:dmdSec>
  <?php $x++ ?>
 @endif

 @if(!empty($fid1_2_amd_fileOriginalName))
  <mets:dmdSec ID="fid1-{{ $x }}-dmd">
    <mets:mdWrap MDTYPE="DC">
      <mets:xmlData>
        <dc:record xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:mods="http://www.loc.gov/mods/v3">
          <dc:title>{{ $fid1_2_dmd_title }}</dc:title>
          <dc:source>{{ $fid1_2_dmd_source }}</dc:source>
          <dc:description>{{ $fid1_2_dmd_description }}</dc:description>
          <dc:identifier>{{ $fid1_2_dmd_identifier }}</dc:identifier>
          @if (!empty($fid1_2_dmd_date)) <dc:date>{{ $fid1_2_dmd_date }}</dc:date> @endif
          <dcterms:tableOfContents>1</dcterms:tableOfContents>
		  @if (!empty($fid1_2_dmd_isFormatOf)) <dcterms:isFormatOf>{{ $fid1_2_dmd_isFormatOf }}</dcterms:isFormatOf> @endif
        </dc:record>
      </mets:xmlData>
    </mets:mdWrap>
  </mets:dmdSec>
  <?php $x++ ?>
@endif
@if(!empty($fid1_3_amd_fileOriginalName))
  <mets:dmdSec ID="fid1-{{ $x }}-dmd">
    <mets:mdWrap MDTYPE="DC">
      <mets:xmlData>
        <dc:record xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:mods="http://www.loc.gov/mods/v3">
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
  @endif
  <mets:amdSec ID="ie-amd">
    <mets:techMD ID="ie-amd-tech">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:techMD>
    <mets:rightsMD ID="ie-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="accessRightsPolicy">
              <record>
                <key id="policyId">AR_EVERYONE</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="ie-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="ie-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>
  <?php $x=1; ?>
  @if(!empty($fid1_1_amd_fileOriginalName))
  <mets:amdSec ID="rep{{ $x }}-amd">
    <mets:techMD ID="rep{{ $x }}-amd-tech">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="generalRepCharacteristics">
              <record>
                <key id="preservationType">PRESERVATION_MASTER</key>
                <key id="usageType">VIEW</key>
                <key id="RevisionNumber">1</key>
                <key id="DigitalOriginal">true</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:techMD>
    <mets:rightsMD ID="rep{{ $x }}-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="accessRightsPolicy">
              <record>
                <key id="policyId">{{ $rep1_amd_rights }}</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="rep{{ $x }}-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="rep{{ $x }}-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>
  <?php $x++; ?>
@endif
@if(!empty($fid1_2_amd_fileOriginalName))
  <mets:amdSec ID="rep{{ $x }}-amd">
    <mets:techMD ID="rep{{ $x }}-amd-tech">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="generalRepCharacteristics">
              <record>
                <key id="preservationType">COMASTER</key>
                <key id="usageType">VIEW</key>
                <key id="RevisionNumber">1</key>
                <key id="DigitalOriginal">true</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:techMD>
    <mets:rightsMD ID="rep{{ $x }}-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="accessRightsPolicy">
              <record>
                <key id="policyId">{{ $rep2_amd_rights }}</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="rep{{ $x }}-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="rep{{ $x }}-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>
  <?php $x++;?>
  @endif
  @if(!empty($fid1_3_amd_fileOriginalName))
  <mets:amdSec ID="rep{{ $x }}-amd">
    <mets:techMD ID="rep{{ $x }}-amd-tech">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="generalRepCharacteristics">
              <record>
                <key id="preservationType">SCREEN</key>
                <key id="usageType">VIEW</key>
                <key id="RevisionNumber">1</key>
                <key id="DigitalOriginal">true</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:techMD>
    <mets:rightsMD ID="rep{{ $x }}-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="accessRightsPolicy">
              <record>
                <key id="policyId">{{ $rep3_amd_rights }}</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="rep{{ $x }}-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="rep{{ $x }}-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>
  @endif
  <?php $x = 1; ?>
  @if(!empty($fid1_1_amd_fileOriginalName))
  <mets:amdSec ID="fid1-{{ $x }}-amd">
    <mets:techMD ID="fid1-{{ $x }}-amd-tech">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="generalFileCharacteristics">
              <record>
                <key id="fileOriginalPath">{{ $fid1_1_amd_fileOriginalPath }}</key>
                <key id="fileOriginalName">{{ $fid1_1_amd_fileOriginalName }}</key>
                <key id="label">{{ $fid1_1_amd_label }}</key>
              </record>
            </section>
            <section id="objectCharacteristics">
              <record>
                <key id="groupID">{{ $fid1_1_amd_groupID }}</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:techMD>
    <mets:rightsMD ID="fid1-{{ $x }}-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="fid1-{{ $x }}-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="fid1-{{ $x }}-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>
  <?php $x++; ?>
  @endif
  @if(!empty($fid1_2_amd_fileOriginalName))
  <mets:amdSec ID="fid1-{{ $x }}-amd">
    <mets:techMD ID="fid1-{{ $x }}-amd-tech">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="generalFileCharacteristics">
              <record>
                <key id="fileOriginalPath">{{ $fid1_2_amd_fileOriginalPath }}</key>
                <key id="fileOriginalName">{{ $fid1_2_amd_fileOriginalName }}</key>
                <key id="label">{{ $fid1_2_amd_label }}</key>
              </record>
            </section>
            <section id="objectCharacteristics">
              <record>
                <key id="groupID">{{ $fid1_2_amd_groupID }}</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:techMD>
    <mets:rightsMD ID="fid1-{{ $x }}-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="fid1-{{ $x }}-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="fid1-{{ $x }}-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>
  <?php $x++; ?>
  @endif
  @if(!empty($fid1_3_amd_fileOriginalName))
  <mets:amdSec ID="fid1-{{ $x }}-amd">
    <mets:techMD ID="fid1-{{ $x }}-amd-tech">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="generalFileCharacteristics">
              <record>
                <key id="fileOriginalPath">{{ $fid1_3_amd_fileOriginalPath }}</key>
                <key id="fileOriginalName">{{ $fid1_3_amd_fileOriginalName }}</key>
                <key id="label">{{ $fid1_3_amd_label }}</key>
              </record>
            </section>
            <section id="objectCharacteristics">
              <record>
                <key id="groupID">{{ $fid1_3_amd_groupID }}</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:techMD>
    <mets:rightsMD ID="fid1-{{ $x }}-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="fid1-{{ $x }}-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="fid1-{{ $x }}-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>
  @endif
  <?php $x = 1; ?>
  <mets:fileSec>
    @if(!empty($fid1_1_amd_fileOriginalName))
    <mets:fileGrp ID="rep{{ $x }}" ADMID="rep{{ $x }}-amd">
      <mets:file ID="fid1-{{ $x }}" DMDID="fid1-{{ $x }}-dmd" ADMID="fid1-{{ $x }}-amd">
        <mets:FLocat LOCTYPE="URL" xlin:href="{{ $rep1_amd_url }}" xmlns:xlin="http://www.w3.org/1999/xlink"/>
      </mets:file>
    </mets:fileGrp>
    <?php $x++; ?>
    @endif
    @if(!empty($fid1_2_amd_fileOriginalName))
    <mets:fileGrp ID="rep{{ $x }}" ADMID="rep{{ $x }}-amd">
      <mets:file ID="fid1-{{ $x }}" DMDID="fid1-{{ $x }}-dmd" ADMID="fid1-{{ $x }}-amd">
        <mets:FLocat LOCTYPE="URL" xlin:href="{{ $rep2_amd_url }}" xmlns:xlin="http://www.w3.org/1999/xlink"/>
      </mets:file>
    </mets:fileGrp>
    <?php $x++; ?>
    @endif
    @if(!empty($fid1_3_amd_fileOriginalName))
	<mets:fileGrp ID="rep{{ $x }}" ADMID="rep{{ $x }}-amd">
      <mets:file ID="fid1-{{ $x }}" DMDID="fid1-{{ $x }}-dmd" ADMID="fid1-{{ $x }}-amd">
        <mets:FLocat LOCTYPE="URL" xlin:href="{{ $rep3_amd_url }}" xmlns:xlin="http://www.w3.org/1999/xlink"/>
      </mets:file>
    </mets:fileGrp>
    @endif
  </mets:fileSec>
  <?php $x = 1; ?>
  @if(!empty($fid1_1_amd_fileOriginalName))
  <mets:structMap ID="rep{{ $x }}-1" TYPE="PHYSICAL">
    <mets:div LABEL="PRESERVATION MASTER">
      <mets:div LABEL="Table of Contents">
        <mets:div LABEL="{{ $rep1_1_label }}" TYPE="FILE">
          <mets:fptr FILEID="fid1-1"/>
        </mets:div>
      </mets:div>
    </mets:div>
  </mets:structMap>
  <?php $x++; ?>
  @endif
  @if(!empty($fid1_2_amd_fileOriginalName))
  <mets:structMap ID="rep{{ $x }}-1" TYPE="PHYSICAL">
    <mets:div LABEL="COMASTER">
      <mets:div LABEL="Table of Contents">
        <mets:div LABEL="{{ $rep2_1_label }}" TYPE="FILE">
          <mets:fptr FILEID="fid1-{{ $x }}"/>
        </mets:div>
      </mets:div>
    </mets:div>
  </mets:structMap>
  <?php $x++; ?>
  @endif
  @if(!empty($fid1_3_amd_fileOriginalName))
   <mets:structMap ID="rep{{ $x }}-1" TYPE="PHYSICAL">
    <mets:div LABEL="SCREEN">
      <mets:div LABEL="Table of Contents">
        <mets:div LABEL="{{ $rep3_1_label }}" TYPE="FILE">
          <mets:fptr FILEID="fid1-{{ $x }}"/>
        </mets:div>
      </mets:div>
    </mets:div>
  </mets:structMap>
  @endif
</mets:mets>
