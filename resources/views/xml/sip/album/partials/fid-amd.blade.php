<mets:amdSec ID="{{ $fidx_y }}-amd">
    <mets:techMD ID="{{ $fidx_y }}-amd-tech">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx">
            <section id="generalFileCharacteristics">
              <record>
                <key id="fileOriginalPath">{{ $fidx_y_amd_fileOriginalPath }}</key>
                <key id="fileOriginalName">{{ $fidx_y_amd_fileOriginalName }}</key>
                <key id="label">{{ $fidx_y_amd_label }}</key>
              </record>
            </section>
            <section id="objectCharacteristics">
              <record>
                <key id="groupID">{{ $fidx_y_amd_groupID }}</key>
              </record>
            </section>
          </dnx>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:techMD>
    <mets:rightsMD ID="{{ $fidx_y }}-amd-rights">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:rightsMD>
    <mets:sourceMD ID="{{ $fidx_y }}-amd-source">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:sourceMD>
    <mets:digiprovMD ID="{{ $fidx_y }}-amd-digiprov">
      <mets:mdWrap MDTYPE="OTHER" OTHERMDTYPE="dnx">
        <mets:xmlData>
          <dnx xmlns="http://www.exlibrisgroup.com/dps/dnx"/>
        </mets:xmlData>
      </mets:mdWrap>
    </mets:digiprovMD>
  </mets:amdSec>
