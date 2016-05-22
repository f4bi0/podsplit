# podsplit
Allows you to split podcast into separate audio files, each file named after the text spoken. 

This program is written in php and can only be used by the command line. Also, you need to have to sox lib installed with the mp3 handler (in case you will be using mp3 files).

# How it works
You must upload an input.mp3 or input.wav file inside your cloned podsplit folder. This will be the podcast itself, containing some stuff being spoken.

Then you upload an input.txt. Each line of this text file must be a separate track you want to extract from the podcast.
Finally you execute the process.php from the terminal like so:

	php process.php 

Now php takes control and waits for you to tell it what to with the podcast. There are four commands available:

- If you type "j .3", for example, and press enter, php will "jump" the cursor and increase the selection by 3 miliseconds ahead.
- If you type "b .3", the posite is done: the selection will be decreased by 3 miliseconds from the end.
- If you type "s", the selected audio is extracted and rendered into a new track, inside the output folder.
- If you type "c {miliseconds}" sets the offset from the begining. This is useful for cutting tails remained by the last extraction.

The idea is to go about moving the selection around until the audio played matches the text presented in the screen (that text is the current line in the input.txt file). Everytime you use the "j" command the selection is increased a little bit and automatically replayed. If you think you have steped into another sentence (supposed to be coupled if another line of the text file) you can always use the "b" command to return the head of the selection.

You will notice that an tmp.wav file will be created. This is the remainder of the input.mp3 which contains the audio content that has not yet been extracted. This allows you to continue your work from where you left. The original input file is never touched.

When you want to process another podcast, make sure you delete the tmp.wav file and replace input.mp3 and text files. Also remove all files from the output folder.

Also, there is a gain.php utility that allows you to increase the volume of all files inside a directory. You must run it like this:
	
	php gain.php {directory} {gain amount}

The files from the directory you specified will not be modified. Instead, a new version of each one will be available inside the "tmp/" folder with the increased volume.

