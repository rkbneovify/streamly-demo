
//
//  SettingsGridCell.swift
//  Streamly
//
//  Created by Rohit Singh on 6/2/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit
protocol Scroll:class {

    func scrollCollectionView(_ index:Int)
}

class SettingsGridCell: UICollectionViewCell {

    

    // MARK: Outlets
    @IBOutlet weak var label:             UILabel!
    @IBOutlet weak var imageView:         UIImageView!
    @IBOutlet weak var content:           GradientView!

    // MARK: Properties

    var representedDataItem: DataItem?
    static let reuseIdentifier = "SettingsGridCell"
    weak var delegate:Scroll?
    
    // MARK: Initialization
    
    override func awakeFromNib() {
        super.awakeFromNib()
        self.unSelectedCell()
        
        
        
    }
    
    // MARK: UICollectionReusableView
    
    override func prepareForReuse() {
        super.prepareForReuse()
        
        // Reset the label's alpha value so it's initially hidden.
       // label.alpha = 0.0
        self.unSelectedCell()
    }
    
    // MARK: UIFocusEnvironment
    
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        /*
         Update the label's alpha value using the `UIFocusAnimationCoordinator`.
         This will ensure all animations run alongside each other when the focus
         changes.
         */
        coordinator.addCoordinatedAnimations({
            if !self.isFocused {
                self.unSelectedCell()
            }
            else {
                self.content.firstColor = UIColor.firstGradientColor
                self.content.secondColor = UIColor.secondGradientColor
                self.content.layer.borderWidth = 1.0
                self.content.layer.borderColor = UIColor.black.cgColor
                self.content.layer.shadowColor = UIColor.black.cgColor
                self.content.layer.shadowRadius = 2.0
                self.content.layer.shadowOpacity = 0.9
                self.content.layer.shadowOffset = CGSize(width: 0, height: 0)

                self.delegate?.scrollCollectionView(self.label.tag - 1)
               
            }
        }, completion: nil)
    }
    
    // MARK: CongigureCell
    
    func configureCell(itemname:String,tag:Int)
    {
        label.text = itemname
        label.tag = tag + 1
        imageView.image = UIImage(named: itemname)
    }

    private func unSelectedCell()
    {
        self.content.layer.borderWidth = 0.0
        self.content.layer.shadowRadius = 0.0
        self.content.layer.shadowOpacity = 0
        self.content.firstColor = UIColor.channelBox
        self.content.secondColor = UIColor.channelBox
    }
}
