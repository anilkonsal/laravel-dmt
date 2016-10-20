<mets:mets xmlns:mets="http://www.loc.gov/METS/">

  <mets:dmdSec ID="ie-dmd">
    <mets:mdWrap MDTYPE="DC">
      <mets:xmlData>
        <dc:record xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
          <dc:identifier>{{ $ie_dmd_identifier }}</dc:identifier>
		  <dc:title>{{ $ie_dmd_title }}</dc:title>
          @if (!empty($ie_dmd_creator)) <dc:creator>{{ $ie_dmd_creator }}</dc:creator> @endif
		  @if (!empty($ie_dmd_source)) <dc:source>{{ $ie_dmd_source }}</dc:source> @endif
		  @if (!empty($ie_dmd_type)) <dc:type>{{ $ie_dmd_type }}</dc:type> @endif
          <dcterms:accessRights>{{ $ie_dmd_accessRights }}</dcterms:accessRights>
		  @if (!empty($ie_dmd_date)) <dc:date>{{ $ie_dmd_date }}</dc:date> @endif
		  @if (!empty($ie_dmd_isFormatOf)) <dcterms:isFormatOf>{{ $ie_dmd_isFormatOf }}</dcterms:isFormatOf> @endif
		  <dcterms:isReferencedBy>ACMS</dcterms:isReferencedBy>
        </dc:record>
      </mets:xmlData>
    </mets:mdWrap>
  </mets:dmdSec>

  <mets:dmdSec ID="fid1-1-dmd">
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

  <mets:dmdSec ID="fid1-2-dmd">
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

  <mets:dmdSec ID="fid1-3-dmd">
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

  <mets:amdSec ID="rep1-amd">
    <mets:techMD ID="rep1-amd-tech">
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
    <mets:rightsMD ID="rep1-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="accessRightsPolicy">
              <record>
                <key id="policyId">1062</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="rep1-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="rep1-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>

  <mets:amdSec ID="rep2-amd">
    <mets:techMD ID="rep2-amd-tech">
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
    <mets:rightsMD ID="rep2-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="accessRightsPolicy">
              <record>
                <key id="policyId">1062</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="rep2-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="rep2-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>

  <mets:amdSec ID="rep3-amd">
    <mets:techMD ID="rep3-amd-tech">
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
    <mets:rightsMD ID="rep3-amd-rights">
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
    <mets:sourceMD ID="rep3-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="rep3-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>

  <mets:amdSec ID="fid1-1-amd">
    <mets:techMD ID="fid1-1-amd-tech">
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
    <mets:rightsMD ID="fid1-1-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="fid1-1-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="fid1-1-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>

  <mets:amdSec ID="fid1-2-amd">
    <mets:techMD ID="fid1-2-amd-tech">
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
    <mets:rightsMD ID="fid1-2-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="fid1-2-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="fid1-2-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>

  <mets:amdSec ID="fid1-3-amd">
    <mets:techMD ID="fid1-3-amd-tech">
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
    <mets:rightsMD ID="fid1-3-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="fid1-3-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="fid1-3-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>

  <mets:fileSec>
    <mets:fileGrp ID="rep1" ADMID="rep1-amd">
      <mets:file ID="fid1-1" DMDID="fid1-1-dmd" ADMID="fid1-1-amd">
        <mets:FLocat LOCTYPE="URL" xlin:href="{{ $rep1_amd_url }}" xmlns:xlin="http://www.w3.org/1999/xlink"/>
      </mets:file>
    </mets:fileGrp>
    <mets:fileGrp ID="rep2" ADMID="rep2-amd">
      <mets:file ID="fid1-2" DMDID="fid1-2-dmd" ADMID="fid1-2-amd">
        <mets:FLocat LOCTYPE="URL" xlin:href="{{ $rep2_amd_url }}" xmlns:xlin="http://www.w3.org/1999/xlink"/>
      </mets:file>
    </mets:fileGrp>
	<mets:fileGrp ID="rep3" ADMID="rep3-amd">
      <mets:file ID="fid1-3" DMDID="fid1-3-dmd" ADMID="fid1-3-amd">
        <mets:FLocat LOCTYPE="URL" xlin:href="{{ $rep3_amd_url }}" xmlns:xlin="http://www.w3.org/1999/xlink"/>
      </mets:file>
    </mets:fileGrp>
  </mets:fileSec>

  <mets:structMap ID="rep1-1" TYPE="PHYSICAL">
    <mets:div LABEL="PRESERVATION MASTER">
      <mets:div LABEL="Table of Contents">
        <mets:div LABEL="{{ $rep1_1_label }}" TYPE="FILE">
          <mets:fptr FILEID="fid1-1"/>
        </mets:div>
      </mets:div>
    </mets:div>
  </mets:structMap>

  <mets:structMap ID="rep2-1" TYPE="PHYSICAL">
    <mets:div LABEL="COMASTER">
      <mets:div LABEL="Table of Contents">
        <mets:div LABEL="{{ $rep2_1_label }}" TYPE="FILE">
          <mets:fptr FILEID="fid1-2"/>
        </mets:div>
      </mets:div>
    </mets:div>
  </mets:structMap>

   <mets:structMap ID="rep3-1" TYPE="PHYSICAL">
    <mets:div LABEL="SCREEN">
      <mets:div LABEL="Table of Contents">
        <mets:div LABEL="{{ $rep3_1_label }}" TYPE="FILE">
          <mets:fptr FILEID="fid1-3"/>
        </mets:div>
      </mets:div>
    </mets:div>
  </mets:structMap>

</mets:mets>
