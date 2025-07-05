<script setup lang="ts">
import type { DocumentFile } from '../types/files'

interface Props {
  documents: DocumentFile[]
  fileType: 'photo' | 'visa' | 'identity_document'
}

const props = defineProps<Props>()

const emit = defineEmits<{
  (e: 'deleteFile', id: number, type: Props['fileType']): void
}>()

const onDeleteClick = (id: number) => {
  emit('deleteFile', id, props.fileType)
}
</script>

<template>
  <div>
    <h3>Uploaded Files</h3>
    <div class="border mt-3 mb-2">
      <div
        v-for="item in documents"
        :key="item.id"
        class="d-flex justify-space-between align-center py-2 px-4"
        :class="{ 'border-b': documents.indexOf(item) < documents.length - 1 }"
      >
        <div class="d-flex align-center">
          <v-icon v-if="!item.url" class="mr-2" size="24" color="gray"
            >mdi-file-document-outline</v-icon
          >
          <img v-else :src="item.url" class="mr-2" width="24" height="24" />
          <span>{{ item.name }}</span>
        </div>
        <v-btn
          icon="mdi-delete-outline"
          variant="plain"
          :ripple="false"
          @click="onDeleteClick(item.id)"
        />
      </div>
    </div>
  </div>
</template>
