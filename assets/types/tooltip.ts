import type { TooltipInterface } from 'flowbite/lib/esm';

export interface TooltipEventDetails {
  tooltip: TooltipInterface;
}

export type TooltipHideEvent = CustomEvent<TooltipEventDetails>;

export type TooltipShowEvent = CustomEvent<TooltipEventDetails>;

export type TooltipToggleEvent = CustomEvent<TooltipEventDetails>;
