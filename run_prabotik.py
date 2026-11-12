#!/usr/bin/env python3
"""
PRABOTIK Launcher
Jalankan aplikasi dengan: python run_prabotik.py
"""
import os
import sys

base_dir = os.path.join(os.path.dirname(os.path.abspath(__file__)), "Penambangan-Data-Puskesmas-Teluk-Sasah-main")
os.chdir(base_dir)
sys.path.insert(0, base_dir)

import app
if __name__ == "__main__":
    port = int(os.environ.get("PORT", 5000))
    print(f"[*] Menjalankan PRABOTIK di http://127.0.0.1:{port}")
    app.app.run(host="0.0.0.0", port=port, debug=False)

