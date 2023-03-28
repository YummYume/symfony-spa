import type { TooltipInterface } from 'flowbite/dist/flowbite.turbo';

export interface TooltipEventDetails {
  tooltip: TooltipInterface | null;
}

export type TooltipHideEvent = CustomEvent<TooltipEventDetails>;

export type TooltipShowEvent = CustomEvent<TooltipEventDetails>;

export type TooltipToggleEvent = CustomEvent<TooltipEventDetails>;
