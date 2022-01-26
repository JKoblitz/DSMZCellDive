#!/usr/bin/Rscript
# install.packages(c("htmlwidgets", "heatmaply"))

## Input Simulation parameters
args = commandArgs(TRUE)
id = args[length(args)]
#id = "2736"
# Libraries

library(pheatmap)
library(dplyr)
library(RColorBrewer)

#color = brewer.pal(n, 'Dark2')

setwd("D:/Users/juk20/Nextcloud/testserver/celldive2/php")
#setwd("/var/www/html/php")

filename <- paste("temp/file_", id, ".csv", sep = '')
data <- read.table(filename, header=T, sep=",")

colnames(data) <- gsub("\\.", "-", colnames(data))

# Matrix format
mat <- data

rownames(mat) <- mat[,1]
mat <- mat %>% dplyr::select(-GENE)

mat <- as.matrix(mat)
groupnames <- mat[1,]
mat <- mat[-1,]

mat_num<-matrix(as.numeric(mat), ncol = ncol(mat), dimnames = list(rownames(mat), colnames(mat)))

# color map for tumours
n = max(c(3,length(unique(groupnames))))
cols = brewer.pal(n, 'Dark2')[1:length(unique(groupnames))]
names(cols) = unique(groupnames)
anno_cols = list(group=cols)
annotation = data.frame(group=groupnames)

# Heatmap
outputfile <- paste("temp/heatmap_", id, ".png", sep = '')
png(outputfile, width=8,height=3.25,units="in",res=300)

# Code
#(rnorm(20))

# Close device
pheatmap(mat_num,
         annotation_col=annotation,
         cluster_col=F,
         angle_col=90, 
         annotation_colors=anno_cols, 
         #border_color=NA, 
         clustering_method="ward.D2")


dev.off()
#saveWidget(p, file=outputfile, selfcontained=FALSE,background = "transparent", libdir='files')

#warnings()
