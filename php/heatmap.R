#!/usr/bin/Rscript
# install.packages(c("htmlwidgets", "heatmaply"))

# Libraries
library(heatmaply)
library(htmlwidgets)
library(RColorBrewer)

## Input Simulation parameters
args = commandArgs(TRUE)
#args = c('Rscript', '--vanilla', '2736', 'row')
#id = "6248"
id = args[length(args)-1]
dendro = args[length(args)]

#setwd("D:/Users/juk20/Nextcloud/testserver/celldive2/php")
setwd("/var/www/html/php")

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

# group colors
groups.unique = unique(groupnames)
N = length(groups.unique)
n = max(c(3,N))
# 
# if (n > 8) {
#         r = ceiling(N/8)
#         cols = brewer.pal(8, 'Dark2')
#         cols = rep(cols,times=r)[1:N]
# } else {
#         cols = brewer.pal(n, 'Dark2')[1:N]
# }

makeColorScale <- function(ncolors, palette = "Dark2") {
        paletteinfo <- RColorBrewer::brewer.pal.info
        if (ncolors > paletteinfo[palette, "maxcolors"]) {
                cols <- colorRampPalette(RColorBrewer::brewer.pal(paletteinfo[palette, "maxcolors"], palette))(ncolors)
        } else if (ncolors < 3) {
                cols <- RColorBrewer::brewer.pal(3, palette)[1:2]
                cols[1:ncolors]
        } else {
                cols <- RColorBrewer::brewer.pal(ncolors, palette)
        }
        cols
}

cols = makeColorScale(n)

names(cols) = groups.unique
#anno_cols = list(group=cols)
#tumour_group <- as.numeric(as.factor(groupnames))

# colorscale = colorRampPalette(rev(brewer.pal(n = 7, name = "RdYlBu")))(100)

outputfile <- paste("temp/heatmap_", id, ".html", sep = '')

# Heatmap
p <- heatmaply(mat_num, 
        dendrogram = dendro,
        #colors = colorscale,
        xlab = "", ylab = "", 
        main = "",
        scale = 'row',
        margins = c(60,100,40,20),
        grid_color = "white", # don't work with plotly
        grid_width = 1, # don't work with plotly
        #grid_gap = 1,
        titleX = FALSE,
        branches_lwd = 0.5,
        label_names = c("Gene", "Cell line", "Value"),
        #fontsize_row = 5, fontsize_col = 5,
        labCol = colnames(mat_num),
        labRow = rownames(mat_num),
        heatmap_layers = theme(axis.line=element_blank()),
        #scale_fill_gradient_fun = ggplot2::scale_fill_gradient2(midpoint = 0),
        #limits = c(-2, 2),
        scale_fill_gradient_fun = ggplot2::scale_fill_gradient2(low='#2166ac',mid='white',high='#b2182b', midpoint = 0),
        #col_side_colors = as.factor(groupnames), #hier brauche ich die Farben
        col_side_colors = data.frame("Tumour group" = as.factor(groupnames), check.names=FALSE),
        col_side_palette = cols#,
        #plot_method = "plotly"
        )
p.widget <- as_widget(p)
p.json.structure <- list(x = p.widget$x, evals=list())

json.content <- jsonlite::toJSON(p.json.structure, force=TRUE, auto_unbox=TRUE)
outputfile <- paste("temp/heatmap_", id, ".json", sep = '')
write(json.content, outputfile)
# saveWidget(p, file=outputfile, selfcontained=FALSE, background = "transparent", libdir='files')

#warnings()
