import React, { useCallback } from "react";
import { Box, Typography, IconButton } from "@mui/material";
import InsertDriveFileOutlinedIcon from "@mui/icons-material/InsertDriveFileOutlined";
import DeleteOutlineIcon from "@mui/icons-material/DeleteOutline";
import type { DocumentFile, DocumentType } from "../../types/files";

interface FileListItemProps {
  documents: DocumentFile[];
  fileType: DocumentType;
  onDeleteFile: (id: number, type: DocumentType) => void;
}

const FileListItem: React.FC<FileListItemProps> = ({
  documents,
  fileType,
  onDeleteFile,
}) => {
  const onDeleteClick = useCallback(
    (id: number) => {
      onDeleteFile(id, fileType);
    },
    [onDeleteFile, fileType]
  );

  return (
    <Box>
      <Typography variant="h6" component="h3" mb={1}>
        Uploaded Files
      </Typography>
      <Box border={1} borderColor="grey.300" mt={1.5} mb={1}>
        {documents.map((item, index) => (
          <Box
            key={item.id}
            display="flex"
            justifyContent="space-between"
            alignItems="center"
            py={1}
            px={2}
            sx={{
              borderBottom:
                index < documents.length - 1 ? "1px solid #e0e0e0" : "none",
            }}
          >
            <Box display="flex" alignItems="center">
              {item.url ? (
                <img
                  src={item.url}
                  alt={item.name}
                  style={{ marginRight: 8, width: 24, height: 24 }}
                />
              ) : (
                <InsertDriveFileOutlinedIcon sx={{ mr: 1, fontSize: 24 }} />
              )}
              <Typography variant="body1">{`${item.name
                .substring(0, 24)
                .trim()}...`}</Typography>
            </Box>
            <IconButton size="small" onClick={() => onDeleteClick(item.id)}>
              <DeleteOutlineIcon />
            </IconButton>
          </Box>
        ))}
      </Box>
    </Box>
  );
};

export default FileListItem;
