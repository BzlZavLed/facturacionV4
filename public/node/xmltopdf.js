import {
    installPdfMake,
    GenericCfdiTranslator,
    PdfMakerBuilder,
    CfdiData,
} from '@nodecfdi/cfdi-to-pdf';
import {
    XmlNodeUtils,
    install,
} from '@nodecfdi/cfdiutils-common';
import {
    DOMImplementation,
    XMLSerializer,
    DOMParser
} from '@xmldom/xmldom';
import PdfPrinter from 'pdfmake';
import { join } from 'path';
import { readFileSync } from 'fs';

var args = process.argv.slice(2);


//const inputCfdiPath = '/xml_ejemplos/xmlEjemplo.xml';
const cfdiSourceString = 'cadenaOrigen';
const outputCfdiPath = '/xml_ejemplos/xmlEjemplo.pdf';

// from version 1.2.x on @nodecfdi/cfdiutils-common required install dom resolution
install(new DOMParser(), new XMLSerializer(), new DOMImplementation());

// PDFMAKE on nodejs require font path not included on distributable files
installPdfMake(new PdfPrinter({
    Roboto: {
        normal: join('.', 'fonts', 'Roboto-Regular.ttf'),
        bold: join('.', 'fonts', 'Roboto-Medium.ttf'),
        italics: join('.', 'fonts', 'Roboto-Italic.ttf'),
        bolditalics: join('.', 'fonts', 'Roboto-MediumItalic.ttf'),
    }
}));

const xml = args[0];
const comprobante = XmlNodeUtils.nodeFromXmlString(xml);
const cfdiData = new CfdiData(comprobante, '', cfdiSourceString, 'mylogoBase64');

const builder = new PdfMakerBuilder(new GenericCfdiTranslator());
await builder.build(cfdiData, outputCfdiPath);

console.log('PDF generated at: ' + outputCfdiPath);



