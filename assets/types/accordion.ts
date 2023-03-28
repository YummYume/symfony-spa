import type { AccordionInterface } from 'flowbite';

export interface AccordionEventDetails {
  accordion: AccordionInterface | null;
}

export type AccordionOpenEvent = CustomEvent<AccordionEventDetails>;

export type AccordionCloseEvent = CustomEvent<AccordionEventDetails>;

export type AccordionToggleEvent = CustomEvent<AccordionEventDetails>;
