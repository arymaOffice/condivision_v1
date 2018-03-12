

<?php

$righe = '';
   
   

for ($i=0;$i<20;$i++) {
  $righe .= '<Row ss:AutoFitHeight="0">
    <Cell><Data ss:Type="Number">'.$i.'</Data></Cell>
    <Cell><Data ss:Type="String">16/15/2009</Data></Cell>
    <Cell><Data ss:Type="Number">'.$i.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$i.'</Data></Cell>
    <Cell><Data ss:Type="Number">66</Data></Cell>
    <Cell><Data ss:Type="Number">55</Data></Cell>
    <Cell><Data ss:Type="Number">644</Data></Cell>
    <Cell><Data ss:Type="Number">'.$i.'</Data></Cell>
    <Cell ss:StyleID="s65"><Data ss:Type="DateTime">2008-11-10T00:00:00.000</Data></Cell>
    <Cell><Data ss:Type="String"><p>dsdsddsdsdsds</p></Data></Cell>
   </Row>';
/*$dati .= '<Row>
    <Cell><Data ss:Type="Number">'.$i.'CIRO</Data></Cell>
    <Cell><Data ss:Type="String">CAVOLO</Data></Cell>
   </Row>';
*/} 



$dati = '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <LastAuthor>Michele</LastAuthor>
  <LastPrinted>2009-11-23T11:26:50Z</LastPrinted>
  <Created>2009-11-23T10:48:29Z</Created>
  <LastSaved>2009-11-23T11:25:31Z</LastSaved>
  <Version>12.00</Version>
 </DocumentProperties>
 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
  <Colors>
   <Color>
    <Index>39</Index>
    <RGB>#E3E3E3</RGB>
   </Color>
  </Colors>
 </OfficeDocumentSettings>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>12270</WindowHeight>
  <WindowWidth>24795</WindowWidth>
  <WindowTopX>240</WindowTopX>
  <WindowTopY>90</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s62">
   <Interior ss:Color="#FFFF00" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s63">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom" ss:WrapText="1"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#000000"
    ss:Bold="1"/>
   <Interior ss:Color="#D8D8D8" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s64">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom" ss:WrapText="1"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#000000"
    ss:Bold="1"/>
   <Interior ss:Color="#D8D8D8" ss:Pattern="Solid"/>
   <NumberFormat ss:Format="Short Date"/>
  </Style>
  <Style ss:ID="s65">
   <NumberFormat ss:Format="Short Date"/>
  </Style>
  <Style ss:ID="s66">
   <Interior ss:Color="#A5A5A5" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s67">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#000000"
    ss:Bold="1"/>
   <Interior ss:Color="#D8D8D8" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s68">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom" ss:WrapText="1"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#000000"
    ss:Bold="1"/>
   <Interior ss:Color="#D8D8D8" ss:Pattern="Solid"/>
   <NumberFormat ss:Format="_-* #,##0.00_-;\-* #,##0.00_-;_-* &quot;-&quot;??_-;_-@_-"/>
  </Style>
  <Style ss:ID="s71">
   <NumberFormat ss:Format="[$-410]mmmm\-yy;@"/>
  </Style>
  <Style ss:ID="s73">
   <Alignment ss:Horizontal="Right" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s74">
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"
    ss:Bold="1"/>
   <NumberFormat ss:Format="Short Date"/>
  </Style>
  <Style ss:ID="s75">
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"
    ss:Bold="1"/>
  </Style>
  <Style ss:ID="s76">
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"
    ss:Underline="Single"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="prova">
  <Table ss:ExpandedColumnCount="13" ss:ExpandedRowCount="25" x:FullColumns="1"
   x:FullRows="1" ss:DefaultRowHeight="15">
   <Column ss:AutoFitWidth="0" ss:Width="90"/>
   <Column ss:AutoFitWidth="0" ss:Width="77.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="46.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="75" ss:Span="1"/>
   <Column ss:Index="6" ss:AutoFitWidth="0" ss:Width="44.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="47.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="81"/>
   <Column ss:AutoFitWidth="0" ss:Width="60"/>
   <Column ss:AutoFitWidth="0" ss:Width="67.5"/>
   <Row ss:AutoFitHeight="0">
    <Cell ss:StyleID="s73"><Data ss:Type="String">Operatore:</Data></Cell>
    <Cell ss:StyleID="s75"><Data ss:Type="String">BK Cinisello</Data></Cell>
    <Cell><Data ss:Type="String">Periodo:</Data></Cell>
    <Cell ss:StyleID="s71"><Data ss:Type="DateTime">2009-11-01T00:00:00.000</Data></Cell>
    <Cell ss:Index="8" ss:StyleID="s73"><Data ss:Type="String">Stampato il:</Data></Cell>
    <Cell ss:StyleID="s74"><Data ss:Type="DateTime">2009-10-10T00:00:00.000</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="24" ss:StyleID="s62">
    <Cell ss:StyleID="s63"><Data ss:Type="String">TOTALE N. SCONTRINI</Data></Cell>
    <Cell ss:StyleID="s64"><Data ss:Type="String">DATA CORRISPETTIVO</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String">EURO</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String">BUONI PASTO SCONTRINO BATTUTO</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String">BUONI PASTO VALORE FACCIALE</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String">POS</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String">CORRISPETTIVO NETTO</Data></Cell>
    <Cell ss:StyleID="s64"><Data ss:Type="String">TOTALE VERSATO</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String">DATA VERSAMENTO</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String">NOTE</Data></Cell>
   </Row>
 '.$righe.'
  
   <Row ss:AutoFitHeight="0" ss:StyleID="s66"/>
   <Row ss:AutoFitHeight="0" ss:Height="23.25">
    <Cell ss:StyleID="s67"><Data ss:Type="String">TOT. SCONTRINI</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String"> </Data></Cell>
    <Cell ss:StyleID="s68"><Data ss:Type="String">TOT.CORRISP.LORDO</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String">TOT.SCONTRINO BUONI PASTO</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String">TOT. VALORE BUONI PASTO</Data></Cell>
    <Cell ss:StyleID="s63"/>
    <Cell ss:StyleID="s63"><Data ss:Type="String">TOT. CORR. NETTO</Data></Cell>
    <Cell ss:StyleID="s64"><Data ss:Type="String">TOTALE VERSATO</Data></Cell>
    <Cell ss:StyleID="s63"><Data ss:Type="String">Differenza</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0">
    <Cell><Data ss:Type="Number">391</Data></Cell>
    <Cell ss:Index="3"><Data ss:Type="Number">500</Data></Cell>
    <Cell><Data ss:Type="Number">500</Data></Cell>
    <Cell><Data ss:Type="Number">600</Data></Cell>
    <Cell ss:Index="7"><Data ss:Type="Number">5200</Data></Cell>
    <Cell><Data ss:Type="Number">6000</Data></Cell>
    <Cell><Data ss:Type="Number">500</Data></Cell>
   </Row>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Layout x:Orientation="Landscape"/>
    <Header x:Margin="0.31496062992125984"/>
    <Footer x:Margin="0.31496062992125984"/>
    <PageMargins x:Bottom="0.74803149606299213" x:Left="0.70866141732283472"
     x:Right="0.70866141732283472" x:Top="0.74803149606299213"/>
   </PageSetup>
   <Unsynced/>
   <Print>
    <ValidPrinterInfo/>
    <PaperSizeIndex>9</PaperSizeIndex>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>5</ActiveRow>
     <ActiveCol>2</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
';


echo $dati;   

?>