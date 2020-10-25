<?php


namespace App\Serializer;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ProductNormalizer implements ContextAwareNormalizerInterface
{

    private $normalizer;
    private $urlHelper;

    public function __construct(
        ObjectNormalizer $normalizer,
        UrlHelper $urlHelper
    ) {
        $this->normalizer   = $normalizer;
        $this->urlHelper    = $urlHelper;
    }

    public function normalize($product, $format = [], array $context = [])
    {
        $data = $this->normalizer->normalize($product, $format, $context);
        if(!empty($product->getImage())){
            $data['image'] = $this->urlHelper->getAbsoluteUrl('/storage/default/' . $product->getImage());
        }
        return $data;
    }

    public function supportsNormalization($data, $format = [], array $context = [])
    {
        return $data instanceof Product;
    }
}