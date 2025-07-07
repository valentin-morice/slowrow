import { Card, CardContent, Typography, Button, styled } from "@mui/material";
import CloudUploadIcon from "@mui/icons-material/CloudUpload";
import FileListItem from "./FileListItem";
import type { DocumentFile, DocumentType } from "../../types/files";

const VisuallyHiddenInput = styled("input")({
  clip: "rect(0 0 0 0)",
  clipPath: "inset(50%)",
  height: 1,
  overflow: "hidden",
  position: "absolute",
  bottom: 0,
  left: 0,
  whiteSpace: "nowrap",
  width: 1,
});

interface DocumentSectionProps {
  title: string;
  documentType: DocumentType;
  documents: DocumentFile[];
  onUpload: (type: DocumentType, file: File) => void;
  onDelete: (id: number, type: DocumentType) => void;
}

export default function DocumentSection({
  title,
  documentType,
  documents,
  onUpload,
  onDelete,
}: DocumentSectionProps) {
  const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      onUpload(documentType, file);
    }
    event.target.value = "";
  };

  return (
    <Card>
      <CardContent>
        <Typography variant="h6" component="h2" mb={2}>
          {title}
        </Typography>
        {!documents.length ? (
          <Button
            component="label"
            role={undefined}
            variant="contained"
            tabIndex={-1}
            startIcon={<CloudUploadIcon />}
          >
            Upload file
            <VisuallyHiddenInput type="file" onChange={handleFileChange} />
          </Button>
        ) : (
          <FileListItem
            documents={documents}
            fileType={documentType}
            onDeleteFile={onDelete}
          />
        )}
      </CardContent>
    </Card>
  );
}
