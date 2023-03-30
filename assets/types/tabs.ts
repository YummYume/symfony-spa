import type { TabsInterface, TabItem } from 'flowbite/lib/esm';

export interface TabsEventDetails {
  tabs: TabsInterface;
  item: TabItem;
}

export type TabsShowEvent = CustomEvent<TabsEventDetails>;
