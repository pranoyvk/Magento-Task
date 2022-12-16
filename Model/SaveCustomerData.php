<?php

declare(strict_types = 1);

namespace CustomCommand\ImportCustomerData\Model;

class SaveCustomerData
{
    /**
     * @var CustomerFactory
     */
    private $modelFactory;
    
    /**
     * @var Customer
     */
    private $resourceModel;

    /**
     * @param CustomerFactory $modelFactory
     * @param Customer $resourceModel
     */
    public function __construct(
        \Magento\Customer\Model\CustomerFactory $modelFactory,
        \Magento\Customer\Model\ResourceModel\Customer $resourceModel,
    ) {
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * Save customer data
     *
     * @param array $data
     */
    public function save(array $data) :void
    {
        $model = $this->modelFactory->create();
        foreach ($data as $value) {
            if (is_array($value)) {
                $model->setData($value);
                $this->resourceModel->save($model);
            }
        }
    }
}
