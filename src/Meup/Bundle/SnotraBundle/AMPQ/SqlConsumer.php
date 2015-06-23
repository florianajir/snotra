<?php
namespace Meup\Bundle\SnotraBundle\AMPQ;

use JMS\Serializer\Serializer;
use Meup\Bundle\SnotraBundle\DataTransformer\DataTransformerInterface;
use Meup\Bundle\SnotraBundle\Provider\SqlProviderInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

/**
 * Class SqlConsumer
 *
 * @author florianajir <florian@1001pharmacies.com>
 */
class SqlConsumer implements ConsumerInterface
{
    const DEFAULT_MESSAGE_CLASS = 'Meup\DataStructure\Message\AMPQMessage';
    const JSON_FORMAT = 'json';
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var SqlProviderInterface
     */
    private $provider;
    /**
     * @var DataTransformerInterface
     */
    private $transformer;
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param SqlProviderInterface     $provider
     * @param DataTransformerInterface $transformer
     * @param Serializer               $serializer
     * @param LoggerInterface          $logger
     * @param string                   $msgClass
     * @param string                   $format
     */
    public function __construct(
        SqlProviderInterface $provider,
        DataTransformerInterface $transformer,
        Serializer $serializer,
        LoggerInterface $logger,
        $msgClass = self::DEFAULT_MESSAGE_CLASS,
        $format = self::JSON_FORMAT
    )
    {
        $this->provider = $provider;
        $this->transformer = $transformer;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->msgClass = $msgClass;
        $this->format = $format;
    }

    /**
     * @param AMQPMessage $message
     *
     * @return boolean Execution status (true if everything's of, false if message should be re-queued)
     */
    public function execute(AMQPMessage $message)
    {
        $this
            ->logger
            ->debug(
                'SQL Consumer data incoming',
                array(
                    'object class' => get_class($message),
                    'data'         => $message->body
                )
            )
        ;

        /* deserialize the message body */
        $message = $this
            ->serializer
            ->deserialize(
                $message->body,
                $this->msgClass,
                $this->format
            )
        ;

        $data = $this->transformer->prepare(
            json_decode(
                $message->getData(),
                true
            ),
            $message->getType()
        );

        var_dump($data);


//        $this
//            ->logger
//            ->debug(
//                'SQL Consumer data persisting',
//                $data
//            );

        if (strtolower($message->getType()) === "locale") { //for no search index Locale
            return true;
        }


        return true;
    }
}
