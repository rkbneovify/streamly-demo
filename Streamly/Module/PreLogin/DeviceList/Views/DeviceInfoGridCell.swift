//
//  DeviceInfoGridCell.swift
//  Streamly
//
//  Created by Aditya Joshi on 26/07/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import UIKit
import CoreImage

class DeviceInfoGridCell: UICollectionViewCell {
    @IBOutlet weak var deviceNameLabel: UILabel!
    @IBOutlet weak var bgView: UIView!
    @IBOutlet weak var deviceImage: UIImageView!
    @IBOutlet weak var turnOFFImageView: UIImageView!
    
    @IBOutlet weak var blurImageView
    :UIImageView!
    static let identifier = String(describing: DeviceInfoGridCell.self)
    override func awakeFromNib() {
        super.awakeFromNib()
        configureCell()
    }
    
    private func configureCell() {
        deviceNameLabel.font = UIFont.init(name: "HelveticaNeue-Medium", size: 25.0)
        bgView.layer.cornerRadius = 10
        bgView.layer.borderWidth = 1.0
        turnOFFImageView.isHidden = true
        self.blurImageView.backgroundColor = .black.withAlphaComponent(0.50)
        self.blurImageView.isHidden = true
    }
    
    func setupData(model: DeviceInfo) {
        deviceNameLabel.text = model.deviceName
        deviceImage.sd_setImage(with: URL(string: model.deviceImg ?? ""), completed: nil)
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
//                self.bgView.backgroundColor = UIColor.init(red: 89/255, green: 97/255, blue: 100/255, alpha: 1.0)
                self.bgView.borderWidth = 1.0
                self.bgView.borderColor = .clear
                self.blurImageView.isHidden = true
                self.turnOFFImageView.isHidden = true
                //    self.backgroundColor = UIColor.gray
            }
            else {
                self.blurImageView.isHidden = false
                self.turnOFFImageView.isHidden = false
                self.bgView.borderWidth = 1.0
                self.bgView.borderColor = .white
//                self.bgView.backgroundColor = UIColor.lightGray
            }
        }, completion: nil)
    }
}
