import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import ErrorAlert from '../../components/ErrorAlert.vue'

describe('ErrorAlert', () => {
  it('renders title and message', () => {
    const wrapper = mount(ErrorAlert, {
      props: {
        title: 'Gagal',
        message: 'Terjadi kesalahan koneksi',
      },
    })
    expect(wrapper.text()).toContain('Gagal')
    expect(wrapper.text()).toContain('Terjadi kesalahan koneksi')
  })

  it('emits dismiss when dismissible', () => {
    const wrapper = mount(ErrorAlert, {
      props: {
        message: 'Error',
        dismissible: true,
      },
    })
    wrapper.find('button').trigger('click')
    expect(wrapper.emitted('dismiss')).toHaveLength(1)
  })

  it('renders retry button when retryAction provided', () => {
    const wrapper = mount(ErrorAlert, {
      props: {
        message: 'Error',
        retryAction: () => {},
        retryText: 'Coba Lagi',
      },
    })
    expect(wrapper.text()).toContain('Coba Lagi')
  })

  it('has role="alert"', () => {
    const wrapper = mount(ErrorAlert, {
      props: { message: 'Error' },
    })
    expect(wrapper.attributes('role')).toBe('alert')
  })
})
