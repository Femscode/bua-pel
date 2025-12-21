# figma-polish

A small, fast toolkit to batch-clean the HTML/CSS you export from Figma plugins (Anima, Locofy, TeleportHQ, etc.) and make it responsive by default.

## Why?
- Avoid copy/paste per section. Export once, drop files in `/input`, run one command.
- Merge all plugin CSS, convert `px` to `rem`, add fluid typography, prefix & minify.
- Ensure `meta viewport`, add a responsive `.container`, and basic landmarks.
- Keep your own classes/selectors intact. No framework lock-in.

## Quick start
1. **Export your whole design** from your plugin (multi-frame / multi-page export).
2. Put everything in the `input/` folder (HTML, CSS, images, fonts).
3. Run:
   ```bash
   npm install
   npm run build
   ```
4. Open the generated files in `dist/`.

To auto-build when you drop new exports:
```bash
npm run watch
```

## Notes
- PurgeCSS is provided but commented out in `postcss.config.cjs`. Enable only after visually verifying your pages, then re-run the build to aggressively remove unused CSS.
- The base stylesheet (`styles/base.css`) adds:
  - A modern reset
  - Fluid type with `clamp()`
  - A responsive `.container`
  - Utility helpers (`.stack`, `.cluster`, `.grid`)
- The HTML processor will add a `meta viewport`, link `styles.css`, wrap body content in `.container` if not already present, and add light accessibility landmarks.

## Folder structure
```
figma-polish/
  input/    # place your exported HTML/CSS/assets here
  dist/     # output lives here
  styles/   # base.css for responsive defaults
  index.mjs # the build script
```

## Troubleshooting
- **Fonts look different**: Ensure your exported fonts are copied to `input/` and linked properly in the export CSS. The tool merges CSS but doesn't rewrite font URLs—keep relative paths intact.
- **Layout still looks fixed**: If your design uses fixed widths baked into many classes, enable `px→rem` (already on) and adjust max container widths or add your own media queries in `styles/base.css`.
- **Don't want the `.container` wrapper?** Remove `wrapInContainer()` in `index.mjs`.

## License
MIT
