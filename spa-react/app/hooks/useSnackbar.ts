import { useState, useCallback } from "react";

export function useSnackbar() {
  const [visible, setVisible] = useState(false);
  const [message, setMessage] = useState("");
  const [color, setColor] = useState("");

  const showSnackbar = useCallback((msg: string, clr: string) => {
    setMessage(msg);
    setColor(clr);
    setVisible(true);
  }, []);

  const hideSnackbar = useCallback(() => {
    setVisible(false);
  }, []);

  return {
    visible,
    message,
    color,
    showSnackbar,
    hideSnackbar,
  };
}
