<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

final class EditorType extends AbstractType
{
    public const DEFAULT_MESSAGES = [
        'ui' => [
            'blockTunes' => [
                'textVariant' => [
                    'Call-out' => 'form.editor.ui.block_tunes.text_variant.call_out',
                    'Citation' => 'form.editor.ui.block_tunes.text_variant.citation',
                    'Details' => 'form.editor.ui.block_tunes.text_variant.details',
                ],
                'toggler' => [
                    'Click to tune' => 'form.editor.ui.block_tunes.toggler.click_to_tune',
                ],
            ],
            'inlineToolbar' => [
                'converter' => [
                    'Convert to' => 'form.editor.ui.inline_toolbar.converter.convert_to',
                ],
            ],
            'toolbar' => [
                'toolbox' => [
                    'Add' => 'form.editor.ui.toolbar.toolbox.add',
                ],
            ],
        ],
        'toolNames' => [
            'Text' => 'form.editor.tool_names.text',
            'Warning' => 'form.editor.tool_names.warning',
            'Quote' => 'form.editor.tool_names.quote',
            'List' => 'form.editor.tool_names.list',
            'SimpleImage' => 'form.editor.tool_names.simple_image',
            'Color' => 'form.editor.tool_names.color',
            'Underline' => 'form.editor.tool_names.underline',
            'Heading' => 'form.editor.tool_names.heading',
            'Delimiter' => 'form.editor.tool_names.delimiter',
            'Bold' => 'form.editor.tool_names.bold',
            'Italic' => 'form.editor.tool_names.italic',
            'Link' => 'form.editor.tool_names.link',
            'ChangeCase' => 'form.editor.tool_names.change_case',
            'Font Size' => 'form.editor.tool_names.font_size',
        ],
        'tools' => [
            'link' => [
                'Add a link' => 'form.editor.tools.link.add_a_link',
            ],
        ],
    ];

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'autofocus' => false,
            'hide_toolbar' => false,
            'placeholder' => 'form.editor.placeholder',
            'placeholder_params' => [],
            'direction' => 'ltr',
            'messages' => self::DEFAULT_MESSAGES,
            'messages_parameters' => [],
            'quote_placeholder' => 'form.editor.tools.quote.config.quote_placeholder',
            'quote_placeholder_parameters' => [],
            'caption_placeholder' => 'form.editor.tools.quote.config.caption_placeholder',
            'caption_placeholder_parameters' => [],
            'log_level' => 'WARN',
        ]);

        $resolver
            ->setDefined('inline_toolbar')
            ->setDefined('min_height')
        ;

        $resolver->setRequired([
            'autofocus',
            'hide_toolbar',
            'direction',
        ]);

        $resolver
            ->setAllowedTypes('autofocus', 'boolean')
            ->setAllowedTypes('hide_toolbar', 'boolean')
            ->setAllowedTypes('inline_toolbar', ['string[]', 'boolean'])
            ->setAllowedTypes('placeholder', ['string', 'boolean'])
            ->setAllowedTypes('min_height', 'number')
            ->setAllowedTypes('direction', 'string')
            ->setAllowedTypes('messages', 'array')
            ->setAllowedTypes('messages_parameters', 'array')
            ->setAllowedTypes('quote_placeholder', 'string')
            ->setAllowedTypes('quote_placeholder_parameters', 'array')
            ->setAllowedTypes('caption_placeholder', 'string')
            ->setAllowedTypes('caption_placeholder_parameters', 'array')
            ->setAllowedTypes('log_level', 'string')
        ;

        $resolver
            ->setAllowedValues('placeholder', static fn (mixed $value): bool => \is_string($value) || false === $value)
            ->setAllowedValues('direction', ['ltr', 'rtl'])
            ->setAllowedValues('log_level', ['VERBOSE', 'INFO', 'WARN', 'ERROR'])
        ;

        $resolver
            ->setInfo('autofocus', 'Wether to autofocus the editor on load.')
            ->setInfo('inline_toolbar', 'The toolbar for the editor.')
        ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            static function (mixed $value): ?string {
                if (!\is_array($value)) {
                    return $value;
                }

                return json_encode($value);
            },
            static function (mixed $value): ?array {
                if (!\is_string($value)) {
                    return $value;
                }

                return json_decode($value, true);
            }
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['options'] = [
            'autofocus' => $options['autofocus'],
            'hideToolbar' => $options['hide_toolbar'],
            'readonly' => $options['required'],
            'placeholder' => $this->translator->trans($options['placeholder'], $options['placeholder_params'], $options['translation_domain']),
            'i18n' => [
                'direction' => $options['direction'],
                'messages' => $this->translateRecursive($options['messages'], $options['messages_parameters'], $options['translation_domain']),
            ],
            'logLevel' => $options['log_level'],
        ];

        if (isset($options['inline_toolbar'])) {
            $view->vars['options']['inlineToolbar'] = $options['inline_toolbar'];
        }

        if (isset($options['min_height'])) {
            $view->vars['options']['minHeight'] = $options['min_height'];
        }

        if (isset($options['quote_placeholder'])) {
            $view->vars['options']['tools']['quote']['config']['quotePlaceholder'] = $this->translator->trans(
                $options['quote_placeholder'],
                $options['quote_placeholder_parameters'] ?? [],
                $options['translation_domain']
            );
        }

        if (isset($options['caption_placeholder'])) {
            $view->vars['options']['tools']['quote']['config']['captionPlaceholder'] = $this->translator->trans(
                $options['caption_placeholder'],
                $options['caption_placeholder_parameters'] ?? [],
                $options['translation_domain']
            );
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if (\is_array($form->getData())) {
            $view->vars['options']['data'] = $form->getData();
        }
    }

    public function getParent(): string
    {
        return TextareaType::class;
    }

    private function translateRecursive(array $messages, array $messagesParameters = [], ?string $translationDomain = null): array
    {
        foreach ($messages as $key => $message) {
            if (\is_string($message)) {
                $messages[$key] = $this->translator->trans($message, $messagesParameters[$key] ?? [], $translationDomain);
            } elseif (\is_array($message)) {
                $messages[$key] = $this->translateRecursive($message, $messagesParameters[$key] ?? [], $translationDomain);
            }
        }

        return $messages;
    }
}
