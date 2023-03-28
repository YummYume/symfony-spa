import type { TabsInterface, TabItem } from 'flowbite';

export interface TabsEventDetails {
  tabs: TabsInterface;
  item: TabItem;
}

export type TabsShowEvent = CustomEvent<TabsEventDetails>;
