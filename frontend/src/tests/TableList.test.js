import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import TableList from '@/components/TableList.vue';
import { ArrowsUpDownIcon, CubeTransparentIcon, ChatBubbleLeftEllipsisIcon } from '@heroicons/vue/24/outline';

describe('TableList Component', () => {
  const defaultProps = {
    sent: true,
    body: {
      data: [
        { id: 1, name: 'Item 1', status: 'active' },
        { id: 2, name: 'Item 2', status: 'inactive' },
      ],
    },
    header: [
      { key: 'name', title: 'Name' },
      { key: 'status', title: 'Status' },
    ],
    actions: [{ title: 'Edit', action: vi.fn() }],
    order: true,
  };

  const createWrapper = (props = {}) => {
    return mount(TableList, {
      props: {
        ...defaultProps,
        ...props,
      },
    });
  };

  it('renderiza a tabela corretamente quando há dados', () => {
    const wrapper = createWrapper();
    expect(wrapper.find('table').exists()).toBe(true);
    expect(wrapper.findAll('tbody tr')).toHaveLength(2);
  });

  it('exibe uma mensagem de "Sem registros" quando não há dados', () => {
    const wrapper = createWrapper({ body: { data: [] } });
    expect(wrapper.findComponent(CubeTransparentIcon).exists()).toBe(true);
    expect(wrapper.text()).toContain('Não foram localizados registros...');
  });

  it('exibe uma mensagem de "Aplique o filtro" quando `sent` é falso', () => {
    const wrapper = createWrapper({ sent: false });
    expect(wrapper.findComponent(ChatBubbleLeftEllipsisIcon).exists()).toBe(true);
    expect(wrapper.text()).toContain('Aplique o filtro na opção localizar');
  });

  it('renderiza o ícone de ordenação corretamente', () => {
    const wrapper = createWrapper();
    const orderIcon = wrapper.findComponent(ArrowsUpDownIcon);
    expect(orderIcon.exists()).toBe(true);
  });
});
