#!/bin/sh

    THEME_DAY="Solarized Dark ansi"
    THEME_NIGHT="tomorrow-night"
    H=$(date +"%H")
    if [ $H -gt 8 ] && [ $H -lt 18 ]; then
      THEME=$THEME_DAY
    else
      THEME=$THEME_NIGHT
    fi

    defaults write com.apple.Terminal "Default Window Settings" "$THEME"
    defaults write com.apple.Terminal "Startup Window Settings" "$THEME"

#osascript -e "tell application \"Terminal\" to set current settings of front window to settings set \"$THEME\""

