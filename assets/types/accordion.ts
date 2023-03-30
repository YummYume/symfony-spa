import type { AccordionInterface, AccordionItem } from 'flowbite/lib/esm';

export interface AccordionEventDetails {
  accordion: AccordionInterface;
  item: AccordionItem;
}

export type AccordionOpenEvent = CustomEvent<AccordionEventDetails>;

export type AccordionCloseEvent = CustomEvent<AccordionEventDetails>;

export type AccordionToggleEvent = CustomEvent<AccordionEventDetails>;
