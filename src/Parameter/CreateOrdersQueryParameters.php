<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Parameter;

use Prefabcortex\DhlParcelDeShippingV2\Http\QueryParameter;
use Prefabcortex\DhlParcelDeShippingV2\Http\QueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Http\ScalarValue;
use Prefabcortex\DhlParcelDeShippingV2\Model\CreateOrdersDocFormat;
use Prefabcortex\DhlParcelDeShippingV2\Model\CreateOrdersIncludeDocs;
use Prefabcortex\DhlParcelDeShippingV2\Model\CreateOrdersPrintFormat;
use Prefabcortex\DhlParcelDeShippingV2\Model\CreateOrdersRetourePrintFormat;
use Prefabcortex\DhlParcelDeShippingV2\Optional\None;
use Prefabcortex\DhlParcelDeShippingV2\Optional\Option;
use Prefabcortex\DhlParcelDeShippingV2\Optional\Some;

final class CreateOrdersQueryParameters
{
    /**
     * If provided and set to `true`, the input document will be: * validated against JSON schema (/orders/ endpoint) at the API layer. In case of errors, HTTP 400 and details will be returned. * validated against the DHL backend. In that case, no state changes are happening, no data is stored, shipments neither deleted nor created, no labels being returned. The call will return a status (200, 400) for each shipment element.
     */
    private bool $validate = false;
    /**
     * Legacy name **printOnlyIfCodable**. If set to *true*, labels will only be created if an address is encodable. This is only relevant for German consignee addresses. If set to false or left out, addresses, that are not encodable will be printed even though you receive a warning.
     */
    private bool $mustEncode = false;
    /**
     * Legacy name **labelResponseType**. Shipping labels and further shipment documents can be: * __include__: included as base64 encoded data in the response (default) * __URL__: provided as URL reference.
     */
    private CreateOrdersIncludeDocs $includeDocs = CreateOrdersIncludeDocs::include;
    /**
     * **Defines** the **printable** document format to be used for label and manifest documents.
     */
    private CreateOrdersDocFormat $docFormat = CreateOrdersDocFormat::PDF;
    /**
     * If set, label and return label for one shipment will be printed as single PDF document with possibly multiple pages. Else, those two labels come as separate documents. The option does not affect customs documents and COD labels.
     */
    private bool $combine = true;
    /**
     * @var Option<CreateOrdersPrintFormat>
     *                                      **Defines** the print medium for the shipping label. The different option vary from standard paper sizes DIN A4 and DIN A5 to specific label print formats. Specific laser print formats using DIN A5 blanks are: * 910-300-600(-oz) (105 x 205mm) * 910-300-300(-oz) (105 x 148mm) Specific laser print formats **not** using a DIN A5 blank: * 910-300-610 (105 x 208mm) * 100x70mm Specific thermal print formats: * 910-300-600 (103 x 199mm) * 910-300-400 (103 x 150mm) * 100x70mm Please use the different formats as follows. If you do not set the parameter the settings of DHL costumer portal account will be used as default.
     */
    private Option $printFormat;
    /**
     * @var Option<CreateOrdersRetourePrintFormat>
     *                                             **Defines** the print medium for the return shipping label. This parameter is only usable, if you do not use **combined printing**. The different option vary from standard paper sizes DIN A4 and DIN A5 to specific label print formats. Specific laser print formats using DIN A5 blanks are: * 910-300-600(-oz) (105 x 205mm) * 910-300-300(-oz) (105 x 148mm) Specific laser print formats **not** using a DIN A5 blank: * 910-300-610 (105 x 208mm) * 100x70mm Specific thermal print formats: * 910-300-600 (103 x 199mm) * 910-300-400 (103 x 150mm) * 100x70mm Please use the different formats as follows. If you do not set the parameter the settings of DHL costumer portal account will be used as default.
     */
    private Option $retourePrintFormat;
    /**
     * @var Option<bool>
     *                   Defines whether the DHL Logo should be included on the shipping label. **Does not affect return labels**.If not provided, default from user profile will be used.
     */
    private Option $printDhlLogo;
    /**
     * @var Option<bool>
     *                   Defines whether the DHL Logo should be included on the generated **return/retoure** label. Does not affect non-return labels.If not provided, default from user profile will be used.
     */
    private Option $printDhlLogoRetoure;

    public function __construct()
    {
        $this->printFormat = None::create();
        $this->retourePrintFormat = None::create();
        $this->printDhlLogo = None::create();
        $this->printDhlLogoRetoure = None::create();
    }

    public function getValidate(): bool
    {
        return $this->validate;
    }

    public function setValidate(bool $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    public function getMustEncode(): bool
    {
        return $this->mustEncode;
    }

    public function setMustEncode(bool $mustEncode): self
    {
        $this->mustEncode = $mustEncode;

        return $this;
    }

    public function getIncludeDocs(): CreateOrdersIncludeDocs
    {
        return $this->includeDocs;
    }

    public function setIncludeDocs(CreateOrdersIncludeDocs $includeDocs): self
    {
        $this->includeDocs = $includeDocs;

        return $this;
    }

    public function getDocFormat(): CreateOrdersDocFormat
    {
        return $this->docFormat;
    }

    public function setDocFormat(CreateOrdersDocFormat $docFormat): self
    {
        $this->docFormat = $docFormat;

        return $this;
    }

    public function getCombine(): bool
    {
        return $this->combine;
    }

    public function setCombine(bool $combine): self
    {
        $this->combine = $combine;

        return $this;
    }

    /** @return Option<CreateOrdersPrintFormat> */
    public function getPrintFormat(): Option
    {
        return $this->printFormat;
    }

    public function setPrintFormat(CreateOrdersPrintFormat $printFormat): self
    {
        $this->printFormat = Some::create($printFormat);

        return $this;
    }

    /** @return Option<CreateOrdersRetourePrintFormat> */
    public function getRetourePrintFormat(): Option
    {
        return $this->retourePrintFormat;
    }

    public function setRetourePrintFormat(CreateOrdersRetourePrintFormat $retourePrintFormat): self
    {
        $this->retourePrintFormat = Some::create($retourePrintFormat);

        return $this;
    }

    /** @return Option<bool> */
    public function getPrintDhlLogo(): Option
    {
        return $this->printDhlLogo;
    }

    public function setPrintDhlLogo(bool $printDhlLogo): self
    {
        $this->printDhlLogo = Some::create($printDhlLogo);

        return $this;
    }

    /** @return Option<bool> */
    public function getPrintDhlLogoRetoure(): Option
    {
        return $this->printDhlLogoRetoure;
    }

    public function setPrintDhlLogoRetoure(bool $printDhlLogoRetoure): self
    {
        $this->printDhlLogoRetoure = Some::create($printDhlLogoRetoure);

        return $this;
    }

    /**
     * @internal called by the generated operation, not part of this package's public
     *                  contract: it may change in any release
     */
    public function toQueryParameters(): QueryParameters
    {
        $parameters = [];
        $parameters[] = new QueryParameter('validate', new ScalarValue($this->validate), false);
        $parameters[] = new QueryParameter('mustEncode', new ScalarValue($this->mustEncode), false);
        $parameters[] = new QueryParameter('includeDocs', new ScalarValue($this->includeDocs->value), false);
        $parameters[] = new QueryParameter('docFormat', new ScalarValue($this->docFormat->value), false);
        $parameters[] = new QueryParameter('combine', new ScalarValue($this->combine), false);
        if ($this->printFormat->isDefined()) {
            $parameters[] = new QueryParameter('printFormat', new ScalarValue($this->printFormat->get()->value), false);
        }
        if ($this->retourePrintFormat->isDefined()) {
            $parameters[] = new QueryParameter('retourePrintFormat', new ScalarValue($this->retourePrintFormat->get()->value), false);
        }
        if ($this->printDhlLogo->isDefined()) {
            $parameters[] = new QueryParameter('printDhlLogo', new ScalarValue($this->printDhlLogo->get()), false);
        }
        if ($this->printDhlLogoRetoure->isDefined()) {
            $parameters[] = new QueryParameter('printDhlLogoRetoure', new ScalarValue($this->printDhlLogoRetoure->get()), false);
        }

        return new QueryParameters(...$parameters);
    }
}
