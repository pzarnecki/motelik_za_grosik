import os
from pathlib import Path
from PIL import Image

IMG_DIR = Path(__file__).resolve().parents[1] / 'images'

def convert_to_webp(path: Path, quality: int = 80):
    out = path.with_suffix('.webp')
    try:
        with Image.open(path) as im:
            im.save(out, format='WEBP', quality=quality, method=6)
        print(f"Converted: {path.name} -> {out.name}")
    except Exception as e:
        print(f"Failed: {path} ({e})")

def main():
    if not IMG_DIR.exists():
        print(f"Images directory not found: {IMG_DIR}")
        return
    for ext in ('.jpg', '.jpeg', '.png'):
        for p in IMG_DIR.glob(f'*{ext}'):
            convert_to_webp(p, quality=80)

if __name__ == '__main__':
    main()
