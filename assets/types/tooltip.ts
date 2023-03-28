import type { TooltipInterface } from 'flowbite';

export interface TooltipEventDetails {
  tooltip: TooltipInterface | null;
}

export type TooltipHideEvent = CustomEvent<TooltipEventDetails>;

export type TooltipShowEvent = CustomEvent<TooltipEventDetails>;

export type TooltipToggleEvent = CustomEvent<TooltipEventDetails>;
