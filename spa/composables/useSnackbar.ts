import { reactive, toRefs } from 'vue'
import type { SnackbarState } from '../types/files'

export function useSnackbar() {
  const snackbarState = reactive<SnackbarState>({
    visible: false,
    message: '',
    color: '',
  })

  const showSnackbar = (message: string, color: string) => {
    snackbarState.message = message
    snackbarState.color = color
    snackbarState.visible = true
  }

  return {
    ...toRefs(snackbarState),
    showSnackbar,
  }
}
