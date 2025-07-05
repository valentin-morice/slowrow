<script setup lang="ts">
import { onMounted, reactive, watch } from 'vue'
import type { Files, Models, DocumentFile } from '../types/files'
import { useFileApi } from '../composables/useFilesApi'
import { useSnackbar } from '../composables/useSnackbar'
import FileListItem from '../components/FileListItem.vue'

const files = reactive<Files>({
  photo: [],
  visa: [],
  identity_document: [],
})

const models = reactive<Models>({
  photo: null,
  visa: null,
  identity_document: null,
})

const { fetchFiles, uploadFile, deleteFile } = useFileApi()
const { visible, message, color, showSnackbar } = useSnackbar()

onMounted(async () => {
  try {
    const fetchedData = (await fetchFiles()) as Files
    files.photo = fetchedData.photo || []
    files.visa = fetchedData.visa || []
    files.identity_document = fetchedData.identity_document || []
  } catch (error) {
    showSnackbar('Error fetching files.', 'error')
  }
})

watch(
  () => models,
  async (models) => {
    const file = Object.entries(models).find(([, value]) => value !== null)
    if (file && file[1] instanceof File) {
      const index = file[0] as 'photo' | 'visa' | 'identity_document'

      try {
        const uploadedFile: DocumentFile = await uploadFile(index, file[1])

        if (files[index]) {
          files[index]?.push(uploadedFile)
        }

        showSnackbar('File uploaded successfully!', 'success')
      } catch (error) {
        showSnackbar('Error uploading file.', 'error')
      }

      models[index] = null
    }
  },
  { deep: true },
)

const handleDelete = async (id: number, type: 'photo' | 'visa' | 'identity_document') => {
  try {
    await deleteFile(id)
    files[type] = files[type]?.filter((file) => file.id !== id)
    showSnackbar('File deleted successfully!', 'success')
  } catch (error) {
    showSnackbar('Error deleting file.', 'error')
  }
}
</script>

<template>
  <div class="pa-16">
    <div class="d-flex align-baseline mb-6 justify-space-between">
      <h1>SlowRow</h1>
      <p>An <b>AnchorLess</b> feature subset.</p>
    </div>
    <v-row>
      <v-col v-for="(documents, index) in files" cols="12" md="4">
        <v-card>
          <template v-slot:title>{{ index }}</template>
          <template v-slot:item>
            <v-file-input
              v-if="!documents?.length"
              class="mt-3"
              label="File input"
              variant="outlined"
              v-model="models[index]"
            ></v-file-input>
            <FileListItem
              v-else
              :documents="documents"
              :fileType="index"
              @deleteFile="handleDelete"
            />
          </template>
        </v-card>
      </v-col>
    </v-row>

    <v-snackbar v-model="visible" :color="color" timeout="3000">
      {{ message }}
      <template v-slot:actions>
        <v-btn variant="text" @click="visible = false">Close</v-btn>
      </template>
    </v-snackbar>
  </div>
</template>
