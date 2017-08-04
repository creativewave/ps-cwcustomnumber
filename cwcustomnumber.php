<?php

class CWCustomNumber extends Module
{
    /**
     * Invoice and credit slip options fields.
     *
     * @var array
     */
    const OPTIONS = [
        'PS_INVOICE_USE_MONTH' => [
            'type'       => 'bool',
            'title'      => 'Add current month to invoice number', /* ->l('Add current month to invoice number') */
            'default'    => false,
            'validation' => 'isBool',
            'cast'       => 'intval',
            'js'         => [
                'on'  => 'onchange="onChangeUseMonth()"',
                'off' => 'onchange="onChangeUseMonth()"',
            ],
        ],
        'PS_INVOICE_RESET_MONTHLY' => [
            'type'       => 'bool',
            'title'      => 'Reset Invoice progressive number at beginning of the month', /* ->l('Reset Invoice progressive number at beginning of the month') */
            'default'    => false,
            'validation' => 'isBool',
            'cast'       => 'intval',
            'js'         => [
                'on'  => 'onchange="onChangeResetMonthly()"',
                'off' => 'onchange="onChangeResetMonthly()"',
            ],
        ],
        'PS_INVOICE_MONTH_POS' => [
            'type'       => 'radio',
            'title'      => 'Position of the month number', /* ->l('Position of the month number') */
            'choices'    => ['After the year', 'Before the year'], /* ->l('After the year') ->l('Before the year') */
            'default'    => false,
            'validation' => 'isBool',
            'cast'       => 'intval',
        ],
        'PS_CREDIT_SLIP_USE_YEAR' => [
            'type'       => 'bool',
            'title'      => 'Add current year to order slip number', /* ->l('Add current year to order slip number') */
            'default'    => true,
            'validation' => 'isBool',
            'cast'       => 'intval',
            'js'         => [
                'on'  => 'onchange="onChangeUseYear()"',
                'off' => 'onchange="onChangeUseYear()"',
            ],
        ],
        'PS_CREDIT_SLIP_RESET' => [
            'type'       => 'bool',
            'title'      => 'Reset order slip progressive number at beginning of the year', /* ->l('Reset order slip progressive number at beginning of the year') */
            'default'    => false,
            'validation' => 'isBool',
            'cast'       => 'intval',
        ],
        'PS_CREDIT_SLIP_YEAR_POS' => [
            'type'       => 'radio',
            'title'      => 'Position of the year number', /* ->l('Position of the year number') */
            'choices'    => ['After the progressive number', 'Before the progressive number'], /* ->l('After the progressive number') ->l('Before the progressive number') */
            'default'    => false,
            'validation' => 'isBool',
            'cast'       => 'intval',
        ],
        'PS_CREDIT_SLIP_USE_MONTH' => [
            'type'       => 'bool',
            'title'      => 'Add current month to order slip number', /* ->l('Add current month to order slip number') */
            'default'    => false,
            'validation' => 'isBool',
            'cast'       => 'intval',
            'js'         => [
                'on'  => 'onchange="onChangeUseMonth()"',
                'off' => 'onchange="onChangeUseMonth()"',
            ],
        ],
        'PS_CREDIT_SLIP_RESET_MONTHLY' => [
            'type'       => 'bool',
            'title'      => 'Reset order slip progressive number at beginning of the month', /* ->l('Reset order slip progressive number at beginning of the month') */
            'default'    => false,
            'validation' => 'isBool',
            'cast'       => 'intval',
            'js'         => [
                'on'  => 'onchange="onChangeResetMonthly()"',
                'off' => 'onchange="onChangeResetMonthly()"',
            ],
        ],
        'PS_CREDIT_SLIP_MONTH_POS' => [
            'type'       => 'radio',
            'title'      => 'Position of the month number', /* ->l('Position of the month number') */
            'choices'    => ['After the year', 'Before the year'], /* ->l('After the year') ->l('Before the year') */
            'default'    => false,
            'validation' => 'isBool',
            'cast'       => 'intval',
        ],
        'PS_CREDIT_SLIP_LEGAL_FREE_TEXT' => [
            'type'    => 'textareaLang',
            'title'   => 'Legal free text', /* ->l('Legal free text') */
            'desc'    => 'Use this field to display additional text on your credit slip, like specific legal information. It will appear below the payment methods summary.', /* ->l('Use this field to display additional text on your credit slip, like specific legal information. It will appear below the payment methods summary.') */
            'size'    => 50,
            'validation' => 'isMessage',
            'cast'       => 'strval',
        ],
        'PS_CREDIT_SLIP_FREE_TEXT' => [
            'type'       => 'textLang',
            'title'      => 'Footer text', /* ->l('Footer text') */
            'desc'       => 'This text will appear at the bottom of the invoice, below your company details.', /* ->l('This text will appear at the bottom of the invoice, below your company details.') */
            'size'       => 50,
            'validation' => 'isMessage',
            'cast'       => 'strval',
        ],
        'PS_CREDIT_SLIP_MODEL' => [
            'type'       => 'select',
            'title'      => 'Credit slip model', /* ->l('Credit slip model') */
            'desc'       => 'Choose an credit slip model.', /* ->l('Choose an credit slip model.') */
            'list'       => [],
            'identifier' => 'id',
            'default'    => 'order-slip',
            'validation' => 'isTplName',
            'cast'       => 'strval',
        ],
    ];

    /**
     * Registered hooks.
     *
     * @var array
     */
    const HOOKS = [
        'actionAdminInvoicesOptionsModifier',
        'actionAdminSlipOptionsModifier',
        'displayAdminInvoicesOptions',
        'displayAdminSlipOptions',
    ];

    /**
     * @see ModuleCore
     */
    public $name    = 'cwcustomnumber';
    public $tab     = 'billing_invoicing';
    public $version = '1.0.0';
    public $author  = 'Creative Wave';
    public $need_instance = 0;
    public $ps_versions_compliancy = [
        'min' => '1.6',
        'max' => '1.6.99.99',
    ];

    /**
     * Initialize module.
     */
    public function __construct()
    {
        parent::__construct();

        $this->displayName      = $this->l('Custom Number');
        $this->description      = $this->l('Customize invoice and credit slip numbers.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    /**
     * Install module.
     */
    public function install(): bool
    {
        return parent::install()
               and $this->registerHooks(static::HOOKS)
               and $this->setOptions(static::OPTIONS)
               and Db::getInstance()->execute('
                   ALTER TABLE '._DB_PREFIX_.'order_slip
                   ADD number INT(11) NOT NULL AFTER id_order_slip
               ');
    }

    /**
     * Uninstall module.
     */
    public function uninstall(): bool
    {
        $this->removeOptions(array_keys(static::OPTIONS));

        return parent::uninstall()
               and $this->removeOptions(static::OPTIONS)
               and Db::getInstance()->execute('
                   ALTER TABLE '._DB_PREFIX_.'order_slip
                   DROP COLUMN number
               ');
    }

    /**
     * Set options of invoice admin page.
     */
    public function hookActionAdminInvoicesOptionsModifier(array $params)
    {
        $options = &$params['options']['general']['fields'];
        $options['PS_INVOICE_USE_YEAR']['js'] = [
            'on'  => 'onchange="onChangeUseYear()"',
            'off' => 'onchange="onChangeUseYear()"',
        ];
        $position = array_search('PS_INVOICE_YEAR_POS', array_keys($options));
        $options  = array_slice($options, 0, $position + 1, true)
                    + $this->getInvoiceOptions()
                    + array_slice($options, $position + 1, null, true);
    }

    /**
     * Set options for credit slip admin page.
     */
    public function hookActionAdminSlipOptionsModifier(array $params)
    {
        $new_options = $this->getCreditSlipOptions();
        $new_options['PS_CREDIT_SLIP_MODEL']['list'] = $this->getCreditSlipsModels();
        $params['options']['general']['fields'] += $new_options;
    }

    /**
     * Add JS to handle admin invoices options UI.
     */
    public function hookDisplayAdminInvoicesOptions(array $params): string
    {
        return $this->getAdminOptionsScript('INVOICE');
    }

    /**
     * Add JS to handle admin slip options UI.
     */
    public function hookDisplayAdminSlipOptions(array $params): string
    {
        return $this->getAdminOptionsScript('CREDIT_SLIP');
    }

    /**
     * Add hooks.
     */
    protected function registerHooks(array $hooks): bool
    {
        return array_product(array_map([$this, 'registerHook'], $hooks));
    }

    /**
     * Set options.
     */
    protected function setOptions(array $options): bool
    {
        $keys   = array_keys($options);
        $values = $this->getOptionsDefaults($options);

        return array_product(array_map([$this, 'setOption'], $keys, $values));
    }

    /**
     * Set option.
     */
    protected function setOption(string $key, string $value): bool
    {
        return Configuration::updateValue($key, $value);
    }

    /**
     * Remove options.
     */
    protected function removeOptions(array $options): bool
    {
        return array_product(array_map([$this, 'removeOption'], array_keys($options)));
    }

    /**
     * Remove option.
     */
    protected function removeOption(string $key): bool
    {
        return Configuration::deleteByName($key);
    }

    /**
     * Get options defaults.
     */
    protected function getOptionsDefaults(array $options): array
    {
        return array_map([$this, 'getOptionDefault'], $options);
    }

    /**
     * Get option default.
     */
    protected function getOptionDefault(array $option): string
    {
        return $option['default'] ?? '';
    }

    /**
     * Get invoice options.
     */
    protected function getInvoiceOptions(): array
    {
        return array_filter(static::OPTIONS, [$this, 'isInvoiceOptionName'], ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get credit slip options.
     */
    protected function getCreditSlipOptions(): array
    {
        return array_filter(static::OPTIONS, [$this, 'isCreditSlipOptionName'], ARRAY_FILTER_USE_KEY);
    }

    /**
     * Wether or a value is an invoice option name.
     */
    protected function isInvoiceOptionName(string $value): bool
    {
        return 0 === strpos($value, 'PS_INVOICE_');
    }

    /**
     * Wether or a value is a credit slip option name.
     */
    protected function isCreditSlipOptionName(string $value): bool
    {
        return 0 === strpos($value, 'PS_CREDIT_SLIP_');
    }

    /**
     * Get credit slip models (templates).
     *
     * @see AdminInvoicesController::getInvoicesModels()
     */
    protected function getCreditSlipsModels(): array
    {
        $models = [['id' => 'order-slip', 'name' => 'order-slip']];

        $templates_override = $this->getCreditSlipsModelsFromDir(_PS_THEME_DIR_.'pdf/');
        $templates_default  = $this->getCreditSlipsModelsFromDir(_PS_PDF_DIR_);
        $templates = array_merge($templates_default, $templates_override);

        foreach ($templates as $template) {
            $template_name = basename($template, '.tpl');
            $models[] = ['id' => $template_name, 'name' => $template_name];
        }

        return $models;
    }

    /**
     * Get credit slip models (templates) from directory.
     *
     * @see AdminInvoicesController::getInvoicesModelsFromDir()
     */
    protected function getCreditSlipsModelsFromDir(string $directory): array
    {
        $templates = [];

        if (is_dir($directory)) {
            $templates = glob($directory.'order-slip-*.tpl');
        }
        if (!$templates) {
            $templates = [];
        }

        return $templates;
    }

    /**
     * Get Javascript for handling admin options UI.
     */
    protected function getAdminOptionsScript(string $controller): string
    {
        return "<script>
            var onChangeUseYear = (function trigger() {
                if (getE('PS_{$controller}_USE_YEAR_on').checked) {
                    getE('PS_{$controller}_USE_MONTH_on').disabled      = false
                    getE('PS_{$controller}_USE_MONTH_off').disabled     = false
                    getE('PS_{$controller}_RESET_MONTHLY_on').disabled  = false
                    getE('PS_{$controller}_RESET_MONTHLY_off').disabled = false
                } else {
                    if (getE('PS_{$controller}_RESET_MONTHLY_off').checked) {
                        getE('PS_{$controller}_RESET_on').disabled  = false
                        getE('PS_{$controller}_RESET_off').disabled = false
                    }
                    getE('PS_{$controller}_USE_MONTH_on').disabled  = true
                    getE('PS_{$controller}_USE_MONTH_off').disabled = true
                    getE('PS_{$controller}_USE_MONTH_off').checked  = true
                    getE('PS_{$controller}_MONTH_POS_0').disabled   = true
                    getE('PS_{$controller}_MONTH_POS_1').disabled   = true
                }
                return trigger
            })()
            var onChangeUseMonth = (function trigger() {
                if (getE('PS_{$controller}_USE_MONTH_on').checked) {
                    getE('PS_{$controller}_RESET_MONTHLY_on').disabled  = false
                    getE('PS_{$controller}_RESET_MONTHLY_off').disabled = false
                    getE('PS_{$controller}_MONTH_POS_0').disabled       = false
                    getE('PS_{$controller}_MONTH_POS_1').disabled       = false
                } else {
                    getE('PS_{$controller}_MONTH_POS_0').disabled = true
                    getE('PS_{$controller}_MONTH_POS_1').disabled = true
                }
                return trigger
            })()
            var onChangeResetMonthly = (function trigger() {
                if (getE('PS_{$controller}_RESET_MONTHLY_on').checked) {
                    getE('PS_{$controller}_RESET_on').disabled  = true
                    getE('PS_{$controller}_RESET_on').checked   = true
                    getE('PS_{$controller}_RESET_off').disabled = true
                } else {
                    getE('PS_{$controller}_RESET_on').disabled  = false
                    getE('PS_{$controller}_RESET_off').disabled = false
                }
                return trigger
            })()
        </script>";
    }
}
